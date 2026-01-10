/**
 * Universal Media Handler
 * Works across entire project for all media inputs
 */

// Global state for deleted media (for simple forms)
window.deletedMediaIds = window.deletedMediaIds || [];

// Per-section/subsection removed media IDs structure
// Format: { 'sections[0]': [id1, id2], 'sections[0][sub_sections][1]': [id3] }
window.sectionRemovedMediaIds = window.sectionRemovedMediaIds || {};

/**
 * Extract section context from input name
 * Examples:
 * - "sections[0][image][desktop]" -> { sectionIndex: 0, subIndex: null, path: "sections[0]" }
 * - "sections[0][sub_sections][1][image][desktop]" -> { sectionIndex: 0, subIndex: 1, path: "sections[0][sub_sections][1]" }
 */
function extractSectionContext(inputName) {
    if (!inputName || typeof inputName !== 'string') {
        return null;
    }

    // Check if this is a section/subsection input
    const sectionMatch = inputName.match(/sections\[(\d+)\]/);
    if (!sectionMatch) {
        return null;
    }

    const sectionIndex = parseInt(sectionMatch[1]);
    const subsectionMatch = inputName.match(/sub_sections\[(\d+)\]/);
    
    if (subsectionMatch) {
        const subIndex = parseInt(subsectionMatch[1]);
        return {
            sectionIndex: sectionIndex,
            subIndex: subIndex,
            path: `sections[${sectionIndex}][sub_sections][${subIndex}]`,
            isSubsection: true
        };
    } else {
        return {
            sectionIndex: sectionIndex,
            subIndex: null,
            path: `sections[${sectionIndex}]`,
            isSubsection: false
        };
    }
}

/**
 * Preview media files when selected
 */
function previewMediaFiles(input, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    if (!previewContainer) return;

    // When a new file is uploaded, automatically remove existing media for this specific input field
    // This handles the "replace" scenario (only for single-file inputs, not multiple/gallery)
    if (input.name && input.files && input.files.length > 0 && !input.multiple) {
        const context = extractSectionContext(input.name);
        // Find existing media items for this specific input field
        const existingItems = previewContainer.querySelectorAll('.media-existing-item');
        existingItems.forEach(item => {
            const removeBtn = item.querySelector('button[onclick*="removeExistingMedia"]');
            if (removeBtn) {
                // Extract media ID and input name from onclick handler
                // Format: removeExistingMedia(this, 123, 'sections[0][image][desktop]')
                const onclickAttr = removeBtn.getAttribute('onclick') || '';
                const fullMatch = onclickAttr.match(/removeExistingMedia\([^,]+,\s*(\d+)(?:,\s*['"]([^'"]+)['"])?\)/);
                if (fullMatch) {
                    const mediaId = parseInt(fullMatch[1]);
                    const existingInputName = fullMatch[2] || input.name;
                    
                    // Only auto-remove if this existing media belongs to the same input field
                    // For desktop/mobile images, they're different fields, so don't auto-remove
                    // Only auto-remove if it's the exact same field (like icon, single image, etc.)
                    if (existingInputName === input.name || 
                        (context && extractSectionContext(existingInputName)?.path === context.path)) {
                        // For single-image fields (not desktop/mobile pair), auto-remove when replacing
                        // Check if this is a single field (not part of desktop/mobile pair)
                        const isImagePair = input.name.includes('[desktop]') || input.name.includes('[mobile]');
                        if (!isImagePair) {
                            // Automatically remove the existing media when new file is uploaded
                            removeExistingMedia(removeBtn, mediaId, input.name);
                        }
                    }
                }
            }
        });
    }

    // Remove only new upload previews, keep existing media
    const newPreviews = previewContainer.querySelectorAll('.media-new-preview');
    newPreviews.forEach(el => {
        // Revoke object URL to free memory
        const img = el.querySelector('img');
        const video = el.querySelector('video');
        if (img && img.src.startsWith('blob:')) {
            URL.revokeObjectURL(img.src);
        }
        if (video && video.src.startsWith('blob:')) {
            URL.revokeObjectURL(video.src);
        }
        el.remove();
    });

    // Preview new uploads
    const files = Array.from(input.files);
    if (!files.length) return;

    files.forEach((file, index) => {
        const wrapper = document.createElement('div');
        wrapper.classList.add('position-relative', 'd-inline-block', 'media-new-preview', 'me-2', 'mb-2');
        wrapper.style.transition = 'transform 0.2s';

        const objectUrl = URL.createObjectURL(file);
        let previewElement = null;
        let mediaType = 'file';
        let clickable = false;

        // Create preview based on file type
        if (file.type.startsWith('image/')) {
            mediaType = 'image';
            clickable = true;
            previewElement = document.createElement('img');
            previewElement.src = objectUrl;
            previewElement.classList.add('img-thumbnail', 'media-preview-thumbnail');
            previewElement.style.maxWidth = '150px';
            previewElement.style.maxHeight = '150px';
            previewElement.style.objectFit = 'cover';
            previewElement.style.cursor = 'pointer';
            previewElement.style.border = '2px solid transparent';
            previewElement.style.transition = 'all 0.2s';
            previewElement.onmouseover = function() {
                this.style.transform = 'scale(1.05)';
                this.style.borderColor = '#0d6efd';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
            };
            previewElement.onmouseout = function() {
                this.style.transform = 'scale(1)';
                this.style.borderColor = 'transparent';
                this.style.boxShadow = 'none';
            };
            previewElement.onclick = function() {
                if (window.showMediaPreview) {
                    window.showMediaPreview(objectUrl, 'image', file.name);
                }
            };
        } else if (file.type.startsWith('video/')) {
            mediaType = 'video';
            clickable = true;
            previewElement = document.createElement('div');
            previewElement.style.position = 'relative';
            previewElement.style.maxWidth = '200px';
            
            const video = document.createElement('video');
            video.src = objectUrl;
            video.controls = false;
            video.muted = true;
            video.style.width = '100%';
            video.style.maxHeight = '150px';
            video.style.objectFit = 'cover';
            video.style.borderRadius = '4px';
            video.classList.add('img-thumbnail');
            
            // Play button overlay
            const playOverlay = document.createElement('div');
            playOverlay.style.position = 'absolute';
            playOverlay.style.top = '50%';
            playOverlay.style.left = '50%';
            playOverlay.style.transform = 'translate(-50%, -50%)';
            playOverlay.style.background = 'rgba(0,0,0,0.7)';
            playOverlay.style.borderRadius = '50%';
            playOverlay.style.width = '40px';
            playOverlay.style.height = '40px';
            playOverlay.style.display = 'flex';
            playOverlay.style.alignItems = 'center';
            playOverlay.style.justifyContent = 'center';
            playOverlay.style.cursor = 'pointer';
            playOverlay.innerHTML = '<i class="mdi mdi-play text-white"></i>';
            playOverlay.onclick = function(e) {
                e.stopPropagation();
                if (window.showMediaPreview) {
                    window.showMediaPreview(objectUrl, 'video', file.name);
                }
            };
            
            video.onclick = function() {
                if (window.showMediaPreview) {
                    window.showMediaPreview(objectUrl, 'video', file.name);
                }
            };
            
            previewElement.appendChild(video);
            previewElement.appendChild(playOverlay);
        } else {
            // File (PDF, DOC, etc.)
            mediaType = 'file';
            previewElement = document.createElement('div');
            previewElement.classList.add('border', 'rounded', 'p-3', 'bg-light', 'text-center');
            previewElement.style.minWidth = '120px';
            previewElement.style.cursor = 'pointer';
            previewElement.style.transition = 'all 0.2s';
            
            // Get file icon based on extension
            const extension = file.name.split('.').pop().toLowerCase();
            let iconClass = 'mdi-file-document';
            if (['pdf'].includes(extension)) iconClass = 'mdi-file-pdf-box';
            else if (['doc', 'docx'].includes(extension)) iconClass = 'mdi-file-word-box';
            else if (['xls', 'xlsx'].includes(extension)) iconClass = 'mdi-file-excel-box';
            else if (['zip', 'rar', '7z'].includes(extension)) iconClass = 'mdi-folder-zip';
            
            previewElement.innerHTML = `
                <i class="mdi ${iconClass} mdi-48px text-primary mb-2 d-block"></i>
                <small class="text-muted d-block" style="word-break: break-word; max-width: 120px;">${file.name}</small>
                <small class="text-muted">${formatFileSize(file.size)}</small>
            `;
            
            previewElement.onmouseover = function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
            };
            previewElement.onmouseout = function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            };
        }

        wrapper.appendChild(previewElement);

        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0', 'm-1');
        removeBtn.style.width = '28px';
        removeBtn.style.height = '28px';
        removeBtn.style.padding = '0';
        removeBtn.style.borderRadius = '50%';
        removeBtn.style.opacity = '0.9';
        removeBtn.style.zIndex = '10';
        removeBtn.innerHTML = '<i class="fa fa-times"></i>';
        removeBtn.title = 'Remove';
        removeBtn.onclick = function(e) {
            e.stopPropagation();
            // Revoke object URL
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            wrapper.remove();
            // Clear the input if no more previews
            const remaining = previewContainer.querySelectorAll('.media-new-preview');
            if (remaining.length <= 1) {
                input.value = '';
            }
        };
        wrapper.appendChild(removeBtn);

        previewContainer.appendChild(wrapper);
    });
}

/**
 * Format file size to human readable format
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Remove existing media from database
 * @param {HTMLElement} button - The remove button element
 * @param {number} mediaId - The media ID to remove
 * @param {string} inputName - Optional: The input name for context detection
 */
function removeExistingMedia(button, mediaId, inputName) {
    if (!mediaId) return;

    // Try to detect context from input name or from button's closest media input
    let context = null;
    if (inputName) {
        context = extractSectionContext(inputName);
    } else {
        // Try to find the associated input field
        const mediaItem = button.closest('.media-existing-item');
        if (mediaItem) {
            const previewContainer = mediaItem.closest('[id$="_preview"]');
            if (previewContainer) {
                const previewId = previewContainer.id;
                // Extract input ID from preview ID (format: media_inputname_preview -> media_inputname)
                const inputId = previewId.replace('_preview', '');
                const input = document.getElementById(inputId);
                if (input && input.name) {
                    context = extractSectionContext(input.name);
                }
            }
        }
    }

    if (context && context.path) {
        // Handle section/subsection context (group edit form)
        if (!window.sectionRemovedMediaIds[context.path]) {
            window.sectionRemovedMediaIds[context.path] = [];
        }
        if (!window.sectionRemovedMediaIds[context.path].includes(mediaId)) {
            window.sectionRemovedMediaIds[context.path].push(mediaId);
        }
        updateSectionDeletedMediaInputs(context);
    } else {
        // Fallback to global removed media IDs (for standalone section edit and simple forms)
        // For standalone section edit, backend expects: removed_ids[]
        // For other simple forms: deleted_media_ids[]
        if (!window.deletedMediaIds.includes(mediaId)) {
            window.deletedMediaIds.push(mediaId);
            updateDeletedMediaInputs();
        }
    }

    // Remove from UI
    const mediaItem = button.closest('.media-existing-item');
    if (mediaItem) {
        mediaItem.remove();
    }
}

/**
 * Update hidden inputs for deleted media in section/subsection context
 */
function updateSectionDeletedMediaInputs(context) {
    if (!context || !window.sectionRemovedMediaIds[context.path]) {
        return;
    }

    const removedIds = window.sectionRemovedMediaIds[context.path];
    
    // Create or get container for this section/subsection
    const containerId = `removedMediaContainer_${context.path.replace(/[\[\]]/g, '_')}`;
    let container = document.getElementById(containerId);

    if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.className = 'section-removed-media-container';
        container.style.display = 'none'; // Hide visually

        // Find the appropriate parent element
        let parentElement = null;
        if (context.isSubsection) {
            // Find subsection container - try multiple selectors
            // Look for subsection accordion body
            const subsectionAccordion = document.querySelector(`#subsectionsAccordion-${context.sectionIndex}`);
            if (subsectionAccordion) {
                const subsectionItems = subsectionAccordion.querySelectorAll('.subsection-card, .accordion-item');
                if (subsectionItems[context.subIndex]) {
                    parentElement = subsectionItems[context.subIndex].querySelector('.accordion-body');
                }
            }
            // Fallback: find by data attribute
            if (!parentElement) {
                const subsectionCard = document.querySelector(`.subsection-card[data-subsection-id]`);
                if (subsectionCard) {
                    parentElement = subsectionCard.querySelector('.accordion-body') || subsectionCard;
                }
            }
        } else {
            // Find section container - try multiple selectors
            // Look for section accordion body by index
            const sectionAccordion = document.querySelector('#sectionsAccordion');
            if (sectionAccordion) {
                const sectionItems = sectionAccordion.querySelectorAll('.section-card, .accordion-item');
                if (sectionItems[context.sectionIndex]) {
                    parentElement = sectionItems[context.sectionIndex].querySelector('.accordion-body');
                }
            }
            // Fallback: find by data attribute
            if (!parentElement) {
                const sectionCard = document.querySelector(`.section-card[data-section-id]`);
                if (sectionCard) {
                    parentElement = sectionCard.querySelector('.accordion-body') || sectionCard;
                }
            }
        }

        // Final fallback: find form and append at top level (as hidden container)
        if (!parentElement) {
            parentElement = document.querySelector('form') || document.body;
        }

        if (parentElement) {
            parentElement.appendChild(container);
        } else {
            console.warn('Could not find parent element for removed media container', context);
            return;
        }
    }

    if (removedIds.length === 0) {
        // Clear the container if no removed IDs
        container.innerHTML = '';
        return;
    }

    // Generate hidden inputs with correct structure
    container.innerHTML = removedIds
        .map(id => {
            const inputName = context.isSubsection
                ? `sections[${context.sectionIndex}][sub_sections][${context.subIndex}][removed_ids][]`
                : `sections[${context.sectionIndex}][removed_ids][]`;
            return `<input type="hidden" name="${inputName}" value="${id}">`;
        })
        .join('');
}

/**
 * Update hidden inputs for deleted media (global/legacy support)
 * For standalone section edit: uses removed_ids[]
 * For other simple forms: uses deleted_media_ids[]
 */
function updateDeletedMediaInputs() {
    let container = document.getElementById('removedMediaContainer');

    if (!container) {
        container = document.createElement('div');
        container.id = 'removedMediaContainer';
        const form = document.querySelector('form');
        if (form) {
            form.appendChild(container);
        }
    }

    if (window.deletedMediaIds.length === 0) {
        container.innerHTML = '';
        return;
    }

    // Check if this is a standalone section edit form (has route to section update)
    const form = container.closest('form');
    const isStandaloneSectionEdit = form && (
        form.action.includes('/sections/') && form.action.includes('/update') ||
        form.querySelector('input[name="_method"][value="PUT"]')
    );

    // For standalone section edit, use removed_ids[] (matches backend SectionService::update)
    // For other forms, use deleted_media_ids[]
    const inputName = isStandaloneSectionEdit ? 'removed_ids[]' : 'deleted_media_ids[]';

    container.innerHTML = window.deletedMediaIds
        .map(id => `<input type="hidden" name="${inputName}" value="${id}">`)
        .join('');
}

/**
 * Initialize removed media containers for all existing sections/subsections on page load
 */
function initializeSectionRemovedMediaContainers() {
    // Find all file inputs that are part of sections/subsections
    document.querySelectorAll('input[type="file"][name*="sections["]').forEach(input => {
        if (input.name) {
            const context = extractSectionContext(input.name);
            if (context) {
                // Initialize the context path if needed
                if (!window.sectionRemovedMediaIds[context.path]) {
                    window.sectionRemovedMediaIds[context.path] = [];
                }
                // Ensure container exists (will be created on first removal)
            }
        }
    });

    // Also check existing media items for their context
    document.querySelectorAll('.media-existing-item').forEach(item => {
        const removeBtn = item.querySelector('button[onclick*="removeExistingMedia"]');
        if (removeBtn) {
            // Extract input name from onclick if available (third parameter)
            const onclickAttr = removeBtn.getAttribute('onclick') || '';
            const nameMatch = onclickAttr.match(/removeExistingMedia\([^,]+,\s*\d+,\s*['"]([^'"]+)['"]\)/);
            if (nameMatch) {
                const inputName = nameMatch[1];
                const context = extractSectionContext(inputName);
                if (context) {
                    // Initialize the context path if needed
                    if (!window.sectionRemovedMediaIds[context.path]) {
                        window.sectionRemovedMediaIds[context.path] = [];
                    }
                }
            } else {
                // Fallback: try to find associated input
                const previewContainer = item.closest('[id$="_preview"]');
                if (previewContainer) {
                    const previewId = previewContainer.id;
                    const inputId = previewId.replace('_preview', '');
                    const input = document.getElementById(inputId);
                    if (input && input.name) {
                        const context = extractSectionContext(input.name);
                        if (context) {
                            // Initialize the context path if needed
                            if (!window.sectionRemovedMediaIds[context.path]) {
                                window.sectionRemovedMediaIds[context.path] = [];
                            }
                        }
                    }
                }
            }
        }
    });
}

// Make functions globally available
window.previewMediaFiles = previewMediaFiles;
window.removeExistingMedia = removeExistingMedia;
window.updateDeletedMediaInputs = updateDeletedMediaInputs;
window.updateSectionDeletedMediaInputs = updateSectionDeletedMediaInputs;
window.extractSectionContext = extractSectionContext;
window.formatFileSize = formatFileSize;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeSectionRemovedMediaContainers();
});

