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
    newPreviews.forEach(el => el.remove());

    // Preview new uploads
    const files = Array.from(input.files);
    if (!files.length) return;

    files.forEach((file, index) => {
        const wrapper = document.createElement('div');
        wrapper.classList.add('position-relative', 'd-inline-block', 'media-new-preview', 'me-2', 'mb-2');

        // Create preview based on file type
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('img-thumbnail');
            img.style.maxWidth = '120px';
            img.style.maxHeight = '120px';
            img.style.objectFit = 'cover';
            wrapper.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.controls = true;
            video.style.maxWidth = '200px';
            video.classList.add('img-thumbnail');
            wrapper.appendChild(video);
        } else {
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('border', 'rounded', 'p-2', 'bg-light');
            fileDiv.innerHTML = `<i class="fa fa-file me-1"></i> ${file.name}`;
            wrapper.appendChild(fileDiv);
        }

        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0', 'm-1');
        removeBtn.style.width = '24px';
        removeBtn.style.height = '24px';
        removeBtn.style.padding = '0';
        removeBtn.style.borderRadius = '50%';
        removeBtn.innerHTML = '<i class="fa fa-times"></i>';
        removeBtn.onclick = function() {
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

