document.addEventListener("DOMContentLoaded", () => {
    const addAccordionBtn = document.getElementById("addAccordionBtn");
    const accordionContainer = document.getElementById("dynamicAccordion");
    const accordionTemplate = document.getElementById("accordionTemplate")?.innerHTML;
    const subsectionTemplate = document.getElementById("subAccordionTemplate")?.innerHTML;

    let accordionIndex = document.querySelectorAll("#dynamicAccordion > .accordion-item").length;

    // Global state
    window.deletedSectionIds = window.deletedSectionIds || [];
    window.mediaRemovedIds = window.mediaRemovedIds || [];

    // Optimized section type display handler
    function updateSectionFields(selectElement) {
        const index = selectElement.dataset.index;
        const type = parseInt(selectElement.value);

        const fields = {
            content: document.getElementById(`section-content-${index}`),
            image: document.getElementById(`section-image-${index}`),
            video: document.getElementById(`section-video-${index}`),
            icon: document.getElementById(`section-icon-${index}`),
            file: document.getElementById(`section-file-${index}`),
            gallery: document.getElementById(`section-gallery-${index}`)
        };

        // Section types mapping
        const typeMapping = {
            content: [2, 8, 9, 10, 11, 12],
            image: [1, 8],
            video: [4, 10],
            icon: [7, 12],
            file: [5, 11],
            gallery: [6, 9]
        };

        // Update display (optimized single pass)
        Object.keys(fields).forEach(key => {
            if (fields[key]) {
                fields[key].style.display = typeMapping[key].includes(type) ? "block" : "none";
            }
        });
    }

    // Optimized file preview
    function previewFile(input) {
        const files = input.files;
        if (!files.length) return;

        // Extract index from name attribute
        const nameMatch = input.name.match(/sections\[(\d+)\]|sections\[(\d+)\]\[subsections\]\[(\d+)\]/);
        if (!nameMatch) return;

        let previewId = input.name
            .replace(/sections\[(\d+)\]/g, "$1")
            .replace(/\[subsections\]\[(\d+)\]/g, "_subsections_$1")
            .replace(/\[image\]\[desktop\]/g, "")
            .replace(/\[image\]\[mobile\]/g, "")
            .replace(/\[video\]\[desktop\]/g, "")
            .replace(/\[video\]\[mobile\]/g, "")
            .replace(/\[video\]\[poster\]\[desktop\]/g, "")
            .replace(/\[video\]\[poster\]\[mobile\]/g, "")
            .replace(/\[icon\]/g, "")
            .replace(/\[file\]/g, "")
            .replace(/\[gallery\]\[\]/g, "");

        if (input.name.includes("[image][desktop]")) previewId = `preview-image-desktop-${previewId}`;
        else if (input.name.includes("[image][mobile]")) previewId = `preview-image-mobile-${previewId}`;
        else if (input.name.includes("[video][desktop]")) previewId = `preview-video-desktop-${previewId}`;
        else if (input.name.includes("[video][mobile]")) previewId = `preview-video-mobile-${previewId}`;
        else if (input.name.includes("[video][poster][desktop]")) previewId = `preview-video-poster-desktop-${previewId}`;
        else if (input.name.includes("[video][poster][mobile]")) previewId = `preview-video-poster-mobile-${previewId}`;
        else if (input.name.includes("[icon]")) previewId = `preview-icon-${previewId}`;
        else if (input.name.includes("[file]")) previewId = `preview-file-${previewId}`;
        else if (input.name.includes("[gallery]")) previewId = `preview-gallery-${previewId}`;
        else return;

        const previewContainer = document.getElementById(previewId);
        if (!previewContainer) return;

        previewContainer.innerHTML = "";

        // Create previews
        const fragment = document.createDocumentFragment();
        Array.from(files).forEach((file) => {
            const wrapper = document.createElement("div");
            wrapper.classList.add("media-item");
            wrapper.style.marginRight = "10px";
            wrapper.style.marginBottom = "10px";

            const removeBtn = document.createElement("span");
            removeBtn.classList.add("media-remove");
            removeBtn.innerHTML = "&times;";
            removeBtn.onclick = function() {
                wrapper.remove();
                input.value = "";
            };
            wrapper.appendChild(removeBtn);

            if (file.type.startsWith("image/")) {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.classList.add("img-thumbnail");
                wrapper.appendChild(img);
            } else if (file.type.startsWith("video/")) {
                const video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.maxWidth = "200px";
                wrapper.appendChild(video);
            } else {
                const span = document.createElement("span");
                span.textContent = file.name;
                span.style.display = "block";
                span.style.padding = "10px";
                wrapper.appendChild(span);
            }

            fragment.appendChild(wrapper);
        });

        previewContainer.appendChild(fragment);
    }

    // Event delegation for file inputs
    accordionContainer?.addEventListener("change", (e) => {
        if (e.target.type === "file") {
            previewFile(e.target);
        }
    });

    // Delete section/subsection
    window.deleteSection = function (button) {
        const accordionItem = button.closest(".accordion-item");
        if (!accordionItem) return;

        const accordionWrapper = accordionItem.closest("#dynamicAccordion, .subsection-container");
        const isSubsection = accordionWrapper?.classList.contains("subsection-container");

        if (!isSubsection && document.querySelectorAll("#dynamicAccordion > .accordion-item").length <= 1) {
            alert("At least one section is required.");
            return;
        }

        const sectionId = accordionItem.dataset.id;
        if (sectionId && sectionId !== "null") {
            window.deletedSectionIds.push(sectionId);
            updateDeletedInputs();
        }

        accordionItem.remove();
        if (!isSubsection) updateSectionOrder();
    };

    // Remove media
    window.removeMedia = function (button, mediaId) {
        if (mediaId && !window.mediaRemovedIds.includes(mediaId)) {
            window.mediaRemovedIds.push(mediaId);
            updateRemovedMediaInputs();
        }
        button.closest(".media-item")?.remove();
    };

    // Update deleted sections input
    window.updateDeletedInputs = function () {
        const container = document.getElementById("deletedSectionsContainer");
        if (!container) return;
        container.innerHTML = window.deletedSectionIds.map(id =>
            `<input type="hidden" name="deleted_sections_ids[]" value="${id}">`
        ).join('');
    };

    // Update removed media input
    function updateRemovedMediaInputs() {
        const container = document.getElementById("removedMediaContainer");
        if (!container) return;
        container.innerHTML = window.mediaRemovedIds.map(id =>
            `<input type="hidden" name="deleted_media_ids[]" value="${id}">`
        ).join('');
    }

    // Add main section
    if (addAccordionBtn && accordionTemplate) {
        addAccordionBtn.addEventListener("click", () => {
            const newAccordion = accordionTemplate
                .replace(/__INDEX__/g, accordionIndex)
                .replace(/__INDEX_PLUS_ONE__/g, accordionIndex + 1);

            accordionContainer.insertAdjacentHTML("beforeend", newAccordion);

            const newItem = accordionContainer.lastElementChild;
            const newSelect = newItem.querySelector(`select[data-index="${accordionIndex}"]`);
            if (newSelect) {
                newSelect.addEventListener("change", () => updateSectionFields(newSelect));
            }

            setupListeners(newItem);
            accordionIndex++;
        });
    }

    // Add subsection
    function handleAddSubsection(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = e.target.closest(".add-subsection-btn");
        if (!btn || !subsectionTemplate) return;

        const parentIndex = parseInt(btn.dataset.parentIndex);
        const container = document.getElementById(`subsection-container-${parentIndex}`);
        if (!container) return;

        const subIndex = container.querySelectorAll(".accordion-item").length;
        const newSubAccordion = subsectionTemplate
            .replace(/__PARENT_INDEX__/g, parentIndex)
            .replace(/__SUB_INDEX__/g, subIndex)
            .replace(/__SUB_INDEX_PLUS_ONE__/g, subIndex + 1);

        container.insertAdjacentHTML("beforeend", newSubAccordion);

        const newItem = container.lastElementChild;
        const newSelect = newItem.querySelector("select.section-type-select");
        if (newSelect) {
            newSelect.dataset.index = `${parentIndex}_subsections_${subIndex}`;
            newSelect.addEventListener("change", () => updateSectionFields(newSelect));
        }

        setupListeners(newItem);
    }

    // Setup all listeners (optimized)
    function setupListeners(container = document) {
        // Type change
        container.querySelectorAll(".section-type-select").forEach(select => {
            select.addEventListener("change", () => updateSectionFields(select));
            updateSectionFields(select);
        });

        // Delete buttons
        container.querySelectorAll(".delete-section, .delete-subsection").forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                window.deleteSection(btn);
            };
        });

        // Add subsection buttons
        container.querySelectorAll(".add-subsection-btn").forEach(btn => {
            btn.onclick = handleAddSubsection;
        });
    }

    // Initial setup
    setupListeners();

    // Sortable (if available)
    if (typeof Sortable !== "undefined" && accordionContainer) {
        Sortable.create(accordionContainer, {
            handle: ".accordion-header",
            animation: 150,
            onEnd: updateSectionOrder
        });
    }

    // Update section order
    function updateSectionOrder() {
        document.querySelectorAll("#dynamicAccordion > .accordion-item").forEach((item, index) => {
            const orderInput = item.querySelector('input[name$="[order]"]:not([name*="subsections"])');
            if (orderInput) orderInput.value = index;

            const headerBtn = item.querySelector(".accordion-button");
            if (headerBtn) {
                const text = headerBtn.textContent.trim();
                const nameMatch = text.match(/- (.+)$/);
                headerBtn.textContent = `Section ${index + 1}${nameMatch ? ` - ${nameMatch[1]}` : ''}`;
            }

            updateSectionIndices(item, index);
        });
    }

    // Update indices after reorder
    function updateSectionIndices(item, newIndex) {
        item.querySelectorAll("input, select, textarea").forEach(el => {
            if (el.name && el.name.includes("sections[")) {
                el.name = el.name.replace(/sections\[\d+\]/, `sections[${newIndex}]`);
            }
        });

        const typeSelect = item.querySelector(".section-type-select");
        if (typeSelect) typeSelect.dataset.index = newIndex;

        item.querySelectorAll("[id]").forEach(el => {
            if (el.id.includes("section-")) {
                el.id = el.id.replace(/section-(\w+)-\d+/, `section-$1-${newIndex}`);
            }
        });

        const subsectionContainer = item.querySelector(".subsection-container");
        if (subsectionContainer) subsectionContainer.id = `subsection-container-${newIndex}`;

        const addSubBtn = item.querySelector(".add-subsection-btn");
        if (addSubBtn) addSubBtn.dataset.parentIndex = newIndex;

        const collapseDiv = item.querySelector(".accordion-collapse");
        const headerBtn = item.querySelector(".accordion-button");
        const header = item.querySelector(".accordion-header");

        if (collapseDiv) {
            collapseDiv.id = `section-collapse-${newIndex}`;
            collapseDiv.setAttribute("aria-labelledby", `section-header-${newIndex}`);
        }
        if (headerBtn) {
            headerBtn.setAttribute("data-bs-target", `#section-collapse-${newIndex}`);
            headerBtn.setAttribute("aria-controls", `section-collapse-${newIndex}`);
        }
        if (header) header.id = `section-header-${newIndex}`;
    }
});
