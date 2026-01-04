/**
 * Universal Media Handler
 * Works across entire project for all media inputs
 */

// Global state for deleted media
window.deletedMediaIds = window.deletedMediaIds || [];

/**
 * Preview media files when selected
 */
function previewMediaFiles(input, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    if (!previewContainer) return;

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
 */
function removeExistingMedia(button, mediaId) {
    if (mediaId && !window.deletedMediaIds.includes(mediaId)) {
        window.deletedMediaIds.push(mediaId);
        updateDeletedMediaInputs();
    }

    // Remove from UI
    const mediaItem = button.closest('.media-existing-item');
    if (mediaItem) {
        mediaItem.remove();
    }
}

/**
 * Update hidden inputs for deleted media
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

    container.innerHTML = window.deletedMediaIds
        .map(id => `<input type="hidden" name="deleted_media_ids[]" value="${id}">`)
        .join('');
}

// Make functions globally available
window.previewMediaFiles = previewMediaFiles;
window.removeExistingMedia = removeExistingMedia;
window.updateDeletedMediaInputs = updateDeletedMediaInputs;
window.formatFileSize = formatFileSize;

