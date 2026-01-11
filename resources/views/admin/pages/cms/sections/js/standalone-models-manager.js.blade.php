<script>
    (function () {
        'use strict';

        // API Base URL
        const API_BASE_URL = '{{ url("/api") }}';

        // Model type to API endpoint mapping
        const MODEL_API_ENDPOINTS = {
            'pages': '/cms/pages',
            'services': '/services',
            'projects': '/projects',
            'tags': '/tags',
            'partners': '/partners'
        };

        // Section types data from backend
        @php
            $sectionTypesData = [];
            foreach ($sectionTypes as $type) {
                $sectionTypesData[$type->slug] = [
                    'slug' => $type->slug,
                    'fields' => $type->fields ?? []
                ];
            }
        @endphp
        const SECTION_TYPES_DATA = @json($sectionTypesData);

        // Store fetched models cache
        const modelsCache = {};
        const uniqueId = 'standalone';

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            initializeStandaloneModelsManager();
        });

        // Initialize standalone models manager
        function initializeStandaloneModelsManager() {
            const typeSelect = document.getElementById('type');
            const modelsManager = document.getElementById('models-manager-' + uniqueId);

            if (!typeSelect) {
                console.warn('Type select not found');
                return;
            }

            if (!modelsManager) {
                console.warn('Models manager not found');
                return;
            }

            // Check initial state (for edit mode when type is already selected)
            updateModelsManagerVisibility();

            // Monitor type select changes
            typeSelect.addEventListener('change', function () {
                updateModelsManagerVisibility();
                // Update type hidden input in models manager
                const typeInput = document.getElementById('section-type-' + uniqueId);
                if (typeInput) {
                    typeInput.value = this.value;
                    typeInput.setAttribute('data-section-type', this.value);
                }
            });

            // Initialize models manager if visible (for edit mode)
            if (modelsManager.style.display !== 'none') {
                initializeModelsManager();
            }
        }

        // Update models manager visibility based on selected type
        function updateModelsManagerVisibility() {
            const typeSelect = document.getElementById('type');
            const modelsManager = document.getElementById('models-manager-' + uniqueId);

            if (!typeSelect || !modelsManager) {
                return;
            }

            const selectedType = typeSelect.value;

            if (!selectedType) {
                // No type selected, hide manager
                modelsManager.style.display = 'none';
                return;
            }

            const sectionTypeData = SECTION_TYPES_DATA[selectedType];
            const supportsModels = sectionTypeData &&
                Array.isArray(sectionTypeData.fields) &&
                sectionTypeData.fields.includes('model');

            if (supportsModels) {
                modelsManager.style.display = '';
                // Initialize if not already initialized
                if (!modelsManager.dataset.initialized) {
                    initializeModelsManager();
                    modelsManager.dataset.initialized = 'true';
                }
            } else {
                modelsManager.style.display = 'none';
                // Clear form inputs when hiding
                const hasRelationInput = document.getElementById('has_relation-' + uniqueId);
                if (hasRelationInput) hasRelationInput.value = '0';
                const modelTypeInput = document.getElementById('model-type-' + uniqueId);
                if (modelTypeInput) modelTypeInput.value = '';
                // Clear model_data inputs (only those in the models manager)
                const selectedList = document.getElementById('selected-models-' + uniqueId);
                if (selectedList) {
                    selectedList.querySelectorAll('input[name^="model_data"]').forEach(input => input.remove());
                }
            }
        }

        // Initialize models manager functionality
        function initializeModelsManager() {
            const manager = document.getElementById('models-manager-' + uniqueId);
            if (!manager) return;

            // Add event listeners
            const addBtn = manager.querySelector('.add-models-btn');
            const clearBtn = manager.querySelector('.clear-models-btn');
            const modal = document.getElementById('modelsModal-' + uniqueId);

            if (addBtn) {
                addBtn.addEventListener('click', () => openModelsModal());
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', () => clearAllModels());
            }

            // Modal event listeners
            if (modal) {
                const modelTypeSelect = modal.querySelector('.model-type-selector');
                const searchInput = modal.querySelector('.models-search');
                const confirmBtn = modal.querySelector('.confirm-selection-btn');

                if (modelTypeSelect) {
                    modelTypeSelect.addEventListener('change', () => loadAvailableModels());
                }

                if (searchInput) {
                    searchInput.addEventListener('input', debounce(() => filterAvailableModels(), 300));
                }

                if (confirmBtn) {
                    confirmBtn.addEventListener('click', () => confirmModelSelection());
                }

                // Load models when modal opens
                modal.addEventListener('show.bs.modal', () => {
                    loadAvailableModels();
                });
            }

            // Remove model button listeners
            manager.querySelectorAll('.remove-model-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const modelId = this.dataset.modelId;
                    removeModel(modelId);
                });
            });

            // Initialize sortable
            initializeSortable();
        }

        // Initialize sortable list
        function initializeSortable() {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            // Check if Sortable is available (from extended-ui-drag-and-drop.js)
            if (typeof Sortable !== 'undefined') {
                new Sortable(selectedList, {
                    animation: 150,
                    handle: '.mdi-drag-vertical',
                    onEnd: function () {
                        normalizeOrders();
                        updateFormInputs();
                    }
                });
            }
        }

        // Open models selection modal
        function openModelsModal() {
            const modal = document.getElementById('modelsModal-' + uniqueId);
            if (modal && typeof bootstrap !== 'undefined') {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        }

        // Fetch available models from API
        async function loadAvailableModels() {
            const modal = document.getElementById('modelsModal-' + uniqueId);
            if (!modal) return;

            const modelTypeSelect = modal.querySelector('.model-type-selector');
            const availableList = modal.querySelector('.available-models-list');
            const modelType = modelTypeSelect?.value || 'pages';

            if (!availableList) return;

            // Show loading
            availableList.innerHTML = `
            <div class="text-center text-muted py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

            try {
                // Check cache first
                const cacheKey = `${modelType}_all`;
                let models = modelsCache[cacheKey];

                if (!models) {
                    const endpoint = MODEL_API_ENDPOINTS[modelType];
                    if (!endpoint) {
                        throw new Error(`Unknown model type: ${modelType}`);
                    }

                    const response = await fetch(`${API_BASE_URL}${endpoint}`);
                    if (!response.ok) {
                        throw new Error(`Failed to fetch ${modelType}`);
                    }

                    const data = await response.json();
                    // Handle different response formats
                    if (data.data) {
                        if (Array.isArray(data.data)) {
                            models = data.data;
                        } else if (data.data.data && Array.isArray(data.data.data)) {
                            models = data.data.data;
                        } else if (data.data[modelType] && Array.isArray(data.data[modelType])) {
                            models = data.data[modelType];
                        } else if (data.data.pages && Array.isArray(data.data.pages)) {
                            models = data.data.pages;
                        } else if (data.data.services && Array.isArray(data.data.services)) {
                            models = data.data.services;
                        } else if (data.data.projects && Array.isArray(data.data.projects)) {
                            models = data.data.projects;
                        } else if (data.data.tags && Array.isArray(data.data.tags)) {
                            models = data.data.tags;
                        } else {
                            models = [];
                        }
                    } else {
                        models = [];
                    }
                    modelsCache[cacheKey] = models;
                }

                renderAvailableModels(models, modelType);
            } catch (error) {
                console.error('Error loading models:', error);
                availableList.innerHTML = `
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    {{ __('custom.messages.retrieved_failed') }}
                </div>
            `;
            }
        }

        // Get media preview HTML for a model
        function getModelMediaPreview(model) {
            if (!model || typeof model !== 'object') return '';

            let imageUrl = null;
            let mediaType = 'image';
            let mediaIcon = 'mdi-image';

            // Check for single image
            if (model.image && model.image.url) {
                imageUrl = model.image.url;
            } else if (model.images && Array.isArray(model.images) && model.images.length > 0) {
                const firstImage = model.images.find(img => img.url) || model.images[0];
                imageUrl = firstImage.url || (firstImage.media_path && firstImage.name ?
                    `{{ url('storage') }}/${firstImage.media_path}/${firstImage.name}` : null);
            } else if (model.video && model.video.url) {
                imageUrl = model.video.poster?.url || model.video.url;
                mediaType = 'video';
                mediaIcon = 'mdi-video';
            } else if (model.videos && Array.isArray(model.videos) && model.videos.length > 0) {
                const firstVideo = model.videos[0];
                imageUrl = firstVideo.poster?.url || firstVideo.url ||
                    (firstVideo.media_path && firstVideo.name ?
                        `{{ url('storage') }}/${firstVideo.media_path}/${firstVideo.name}` : null);
                mediaType = 'video';
                mediaIcon = 'mdi-video';
            } else if (model.file && model.file.url) {
                imageUrl = model.file.url;
                mediaType = 'file';
                mediaIcon = 'mdi-file-document';
            } else if (model.files && Array.isArray(model.files) && model.files.length > 0) {
                const firstFile = model.files[0];
                imageUrl = firstFile.url || (firstFile.media_path && firstFile.name ?
                    `{{ url('storage') }}/${firstFile.media_path}/${firstFile.name}` : null);
                mediaType = 'file';
                mediaIcon = 'mdi-file-document';
            } else if (model.icon && model.icon.url) {
                imageUrl = model.icon.url;
                mediaType = 'icon';
                mediaIcon = 'mdi-star';
            } else if (model.icons && Array.isArray(model.icons) && model.icons.length > 0) {
                const firstIcon = model.icons[0];
                imageUrl = firstIcon.url || (firstIcon.media_path && firstIcon.name ?
                    `{{ url('storage') }}/${firstIcon.media_path}/${firstIcon.name}` : null);
                mediaType = 'icon';
                mediaIcon = 'mdi-star';
            }

            const modelName = extractModelName(model);
            const altText = modelName || 'Preview';

            if (imageUrl) {
                const safeUrl = imageUrl.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const safeAlt = altText.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                return `
                <div class="model-media-preview me-3 position-relative" style="width: 60px; height: 60px; flex-shrink: 0;">
                    <img src="${safeUrl}"
                         alt="${safeAlt}"
                         class="img-thumbnail rounded"
                         style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                         onclick="if(window.showMediaPreview) window.showMediaPreview('${safeUrl}', '${mediaType}', '${safeAlt}')"
                         onmouseover="this.style.transform='scale(1.1)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         loading="lazy">
                    <div class="d-none align-items-center justify-content-center bg-light rounded position-absolute top-0 start-0"
                         style="width: 100%; height: 100%;">
                        <i class="mdi ${mediaIcon} mdi-24px text-muted"></i>
                    </div>
                    ${mediaType === 'video' ? '<div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 rounded-circle p-1"><i class="mdi mdi-play mdi-12px text-white"></i></div>' : ''}
                </div>
            `;
            } else {
                return `
                <div class="model-media-preview me-3 d-flex align-items-center justify-content-center bg-light rounded"
                     style="width: 60px; height: 60px; flex-shrink: 0;"
                     title="{{ __('custom.words.no_media') ?? 'No media available' }}">
                    <i class="mdi ${mediaIcon} mdi-24px text-muted"></i>
                </div>
            `;
            }
        }

        // Extract model name from model object (handles translatable fields)
        function extractModelName(model) {
            if (!model || typeof model !== 'object') return 'Unknown Model';

            const currentLocale = '{{ app()->getLocale() }}' || 'en';
            const fallbackLocale = currentLocale === 'ar' ? 'en' : 'ar';

            let modelName = '';

            if (model.name) {
                if (typeof model.name === 'object' && model.name !== null) {
                    modelName = model.name[currentLocale] ||
                        model.name[fallbackLocale] ||
                        Object.values(model.name)[0] ||
                        '';
                } else {
                    modelName = model.name;
                }
            }

            if (!modelName && model.title) {
                if (typeof model.title === 'object' && model.title !== null) {
                    modelName = model.title[currentLocale] ||
                        model.title[fallbackLocale] ||
                        Object.values(model.title)[0] ||
                        '';
                } else {
                    modelName = model.title;
                }
            }

            if (!modelName) {
                modelName = model.slug || `Model #${model.id || 'Unknown'}`;
            }

            return String(modelName).trim() || 'Unknown Model';
        }

        // Render available models in modal
        function renderAvailableModels(models, modelType) {
            const modal = document.getElementById('modelsModal-' + uniqueId);
            if (!modal) return;

            const availableList = modal.querySelector('.available-models-list');
            if (!availableList) return;

            const selectedList = document.getElementById('selected-models-' + uniqueId);
            const selectedIds = selectedList ? Array.from(selectedList.querySelectorAll('[data-model-id]'))
                .map(item => parseInt(item.dataset.modelId))
                .filter(id => !isNaN(id)) : [];

            if (models.length === 0) {
                availableList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="mdi mdi-information-outline mdi-48px mb-2"></i>
                    <p>{{ __('custom.words.no_models_available') }}</p>
                </div>
            `;
                return;
            }

            const currentLocale = '{{ app()->getLocale() }}' || 'en';
            const fallbackLocale = currentLocale === 'ar' ? 'en' : 'ar';

            let html = '<div class="list-group">';
            models.forEach(model => {
                const isSelected = selectedIds.includes(model.id);
                const displayName = extractModelName(model);
                const mediaPreview = getModelMediaPreview(model);
                const modelTypeName = model.type || modelType;

                html += `
                <label class="list-group-item d-flex align-items-start model-selection-item">
                    <input type="checkbox"
                           class="form-check-input me-3 mt-2 model-checkbox"
                           value="${model.id}"
                           data-model-id="${model.id}"
                           data-model-name="${displayName.replace(/"/g, '&quot;').replace(/'/g, '&#39;')}"
                           data-model-type="${modelType}"
                           ${isSelected ? 'disabled' : ''}>
                    <div class="flex-grow-1 d-flex align-items-start">
                        ${mediaPreview}
                        <div class="flex-grow-1">
                            <strong>${displayName}</strong>
                            ${isSelected ? '<span class="badge bg-secondary ms-2">{{ __('custom.words.selected') }}</span>' : ''}
                            ${model.slug ? `<small class="text-muted d-block">${model.slug}</small>` : ''}
                        </div>
                    </div>
                </label>
            `;
            });
            html += '</div>';

            availableList.innerHTML = html;
        }

        // Filter available models by search
        function filterAvailableModels() {
            const modal = document.getElementById('modelsModal-' + uniqueId);
            if (!modal) return;

            const searchInput = modal.querySelector('.models-search');
            if (!searchInput) return;

            const searchTerm = searchInput.value.toLowerCase() || '';
            const checkboxes = modal.querySelectorAll('.model-checkbox');

            checkboxes.forEach(checkbox => {
                const label = checkbox.closest('label');
                const text = label.textContent.toLowerCase();
                label.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // Confirm model selection
        function confirmModelSelection() {
            const modal = document.getElementById('modelsModal-' + uniqueId);
            if (!modal) return;

            const checkboxes = modal.querySelectorAll('.model-checkbox:checked:not(:disabled)');

            if (checkboxes.length === 0) {
                alert('{{ __('custom.words.select') }} {{ __('custom.words.models') }}');
                return;
            }

            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            const existingIds = Array.from(selectedList.querySelectorAll('[data-model-id]'))
                .map(item => parseInt(item.dataset.modelId))
                .filter(id => !isNaN(id));

            const modelTypeSelect = modal.querySelector('.model-type-selector');
            const modelType = modelTypeSelect ? modelTypeSelect.value : 'pages';

            const cacheKey = `${modelType}_all`;
            const allModels = modelsCache[cacheKey] || [];

            const newModels = [];
            checkboxes.forEach(checkbox => {
                const modelId = parseInt(checkbox.value);
                if (!existingIds.includes(modelId)) {
                    const fullModel = allModels.find(m => m && m.id === modelId);
                    if (fullModel) {
                        newModels.push(fullModel);
                    } else {
                        const modelName = checkbox.dataset.modelName || 'Unknown Model';
                        const modelTypeName = checkbox.dataset.modelType || modelType;
                        newModels.push({
                            id: modelId,
                            name: modelName,
                            type: modelTypeName,
                            slug: null
                        });
                    }
                }
            });

            if (newModels.length > 0) {
                addModelsToList(newModels);
            }

            // Close modal
            if (typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }

            // Clear search and uncheck all
            const searchInput = modal.querySelector('.models-search');
            if (searchInput) searchInput.value = '';
            checkboxes.forEach(cb => cb.checked = false);
        }

        // Add models to selected list
        function addModelsToList(models) {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            const existingItems = selectedList.querySelectorAll('.selected-model-item');
            let maxOrder = existingItems.length;

            models.forEach(async (model) => {
                maxOrder++;
                const item = createModelItem(model, maxOrder);
                selectedList.appendChild(item);
            });

            const emptyState = selectedList.querySelector('.empty-state');
            if (emptyState) emptyState.remove();

            normalizeOrders();
            updateModelsCount();
            updateFormInputs();
        }

        // Create model item HTML
        function createModelItem(model, order) {
            const item = document.createElement('div');
            item.className = 'selected-model-item mb-2 p-2 border rounded d-flex align-items-center justify-content-between';
            item.dataset.modelId = model.id;

            const modelTypeName = model.type || (model.model_type || 'Model');
            item.dataset.modelType = modelTypeName;

            const index = document.querySelectorAll('#selected-models-' + uniqueId + ' .selected-model-item').length;
            const displayName = extractModelName(model);
            const mediaPreview = getModelMediaPreview(model);

            item.innerHTML = `
            <div class="d-flex align-items-center flex-grow-1">
                <i class="mdi mdi-drag-vertical me-2 text-muted" style="cursor: move;" title="{{ __('custom.words.drag_to_reorder') ?? 'Drag to reorder' }}"></i>
                ${mediaPreview}
                <div class="order-badge me-2 d-flex align-items-center justify-content-center bg-primary text-white rounded"
                     style="width: 30px; height: 30px; font-weight: bold; font-size: 0.875rem; flex-shrink: 0;">
                    ${order}
                </div>
                <div class="flex-grow-1">
                    <strong>${displayName.replace(/"/g, '&quot;').replace(/'/g, '&#39;')}</strong>
                    <small class="text-muted ms-2">(${modelTypeName})</small>
                    ${model.slug ? `<small class="text-muted d-block">${String(model.slug)}</small>` : ''}
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-model-btn"
                    data-model-id="${model.id}"
                    title="{{ __('custom.words.remove') }}">
                <i class="mdi mdi-close"></i>
            </button>
            <input type="hidden"
                   name="model_data[${index}][model_id]"
                   value="${model.id}"
                   data-model-id="${model.id}"
                   class="model-id-input">
            <input type="hidden"
                   name="model_data[${index}][order]"
                   value="${order}"
                   data-model-id="${model.id}"
                   class="order-hidden-input">
        `;

            const removeBtn = item.querySelector('.remove-model-btn');
            removeBtn.addEventListener('click', function () {
                const modelId = this.dataset.modelId;
                removeModel(modelId);
            });

            return item;
        }

        // Remove a model from the list
        function removeModel(modelId) {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            const item = selectedList.querySelector(`[data-model-id="${modelId}"]`);

            if (item) {
                item.remove();
                normalizeOrders();
                updateModelsCount();
                updateFormInputs();
            }
        }

        // Clear all models
        function clearAllModels() {
            if (!confirm('{{ __('custom.words.are_you_sure') }}')) {
                return;
            }

            const selectedList = document.getElementById('selected-models-' + uniqueId);
            selectedList.innerHTML = `
            <div class="empty-state text-center text-muted py-4">
                <i class="mdi mdi-information-outline mdi-48px mb-2"></i>
                <p class="mb-0">{{ __('custom.words.no_models_selected') }}</p>
            </div>
        `;

            updateModelsCount();
            updateFormInputs();
        }

        // Normalize order numbers (1, 2, 3, ...)
        function normalizeOrders() {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            const items = Array.from(selectedList.querySelectorAll('.selected-model-item'));

            items.forEach((item, index) => {
                const newOrder = index + 1;
                const orderHiddenInput = item.querySelector('.order-hidden-input');
                const orderBadge = item.querySelector('.order-badge');

                if (orderHiddenInput) {
                    orderHiddenInput.value = newOrder;
                }

                if (orderBadge) {
                    orderBadge.textContent = newOrder;
                }
            });

            // Re-render hidden inputs with correct indices
            items.forEach((item, index) => {
                const modelId = item.dataset.modelId;
                const orderInput = item.querySelector('.order-hidden-input');
                const modelIdInput = item.querySelector('.model-id-input');

                if (orderInput) {
                    orderInput.name = `model_data[${index}][order]`;
                }
                if (modelIdInput) {
                    modelIdInput.name = `model_data[${index}][model_id]`;
                }
            });
        }

        // Update form inputs (has_relation, model type, etc.)
        function updateFormInputs() {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            const items = selectedList.querySelectorAll('.selected-model-item');
            const hasModels = items.length > 0;

            const hasRelationInput = document.getElementById('has_relation-' + uniqueId);
            if (hasRelationInput) {
                hasRelationInput.value = hasModels ? '1' : '0';
            }

            // Update model type from first item if available
            if (hasModels && items.length > 0) {
                const firstItem = items[0];
                const modelTypeInput = document.getElementById('model-type-' + uniqueId);
                // Model type should be set from the modal selector, but we can update it here if needed
            }
        }

        // Update models count badge
        function updateModelsCount() {
            const selectedList = document.getElementById('selected-models-' + uniqueId);
            if (!selectedList) return;

            const count = selectedList.querySelectorAll('.selected-model-item').length;
            const badge = document.getElementById('models-count-' + uniqueId);
            if (badge) {
                badge.textContent = count;
            }
        }

        // Media preview function (shared with other scripts)
        window.showMediaPreview = function (url, type, altText) {
            const previewModal = document.getElementById('mediaPreviewModal');
            if (!previewModal) return;

            const content = previewModal.querySelector('#mediaPreviewContent');
            const downloadBtn = previewModal.querySelector('#mediaPreviewDownload');

            if (!content) return;

            content.innerHTML = '';
            downloadBtn.style.display = 'none';

            if (type === 'video' || url.match(/\.(mp4|webm|ogg|mov|avi)$/i)) {
                content.innerHTML = `
                <video src="${url}"
                       controls
                       class="img-fluid rounded"
                       style="max-width: 100%; max-height: 70vh;"
                       preload="metadata">
                    {{ __('custom.words.video') }} {{ __('custom.words.not_supported') }}
                </video>
            `;
                downloadBtn.href = url;
                downloadBtn.style.display = 'inline-block';
            } else if (type === 'file' || url.match(/\.(pdf|doc|docx|xls|xlsx|zip|rar)$/i)) {
                const fileName = altText || url.split('/').pop() || 'file';
                content.innerHTML = `
                <div class="d-flex flex-column align-items-center">
                    <i class="mdi mdi-file-document mdi-96px text-primary mb-3"></i>
                    <p class="mb-0"><strong>${fileName}</strong></p>
                    <p class="text-muted small">{{ __('custom.words.click_download') ?? 'Click download to view this file' }}</p>
                </div>
            `;
                downloadBtn.href = url;
                downloadBtn.style.display = 'inline-block';
            } else {
                content.innerHTML = `
                <img src="${url}"
                     alt="${altText || 'Preview'}"
                     class="img-fluid rounded shadow"
                     style="max-width: 100%; max-height: 70vh; object-fit: contain; cursor: zoom-in;">
            `;
                downloadBtn.href = url;
                downloadBtn.style.display = 'inline-block';
            }

            if (typeof bootstrap !== 'undefined') {
                const bsModal = new bootstrap.Modal(previewModal);
                bsModal.show();
            }
        };

        // Debounce helper
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    })();
</script>
