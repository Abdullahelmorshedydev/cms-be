/**
 * Media Service
 * Unified service for handling media uploads (image/images/file/files/video/videos/icon/icons)
 * Works with FormData and axios/fetch API calls
 * 
 * EXAMPLE USAGE:
 * 
 * // Example 1: Using with axios
 * const payload = {
 *   title: "A",
 *   image: fileInput.files[0],
 *   images: Array.from(fileInput2.files),
 *   icon: iconFile,
 *   files: [file1, file2],
 *   video: videoFile,
 *   videos: [video1, video2]
 * };
 * 
 * const response = await MediaService.requestWithMedia(
 *   axios,
 *   { url: '/api/cms/admin/sections', method: 'POST' },
 *   payload
 * );
 * 
 * // Example 2: Generated FormData keys for above payload:
 * // - title: "A"
 * // - image: File
 * // - images[]: File (first)
 * // - images[]: File (second)
 * // - icon: File
 * // - files[]: File (first)
 * // - files[]: File (second)
 * // - video: File
 * // - videos[]: File (first)
 * // - videos[]: File (second)
 * 
 * // Example 3: Nested keys
 * const payload2 = {
 *   section: {
 *     icon: file,
 *     images: [file1, file2]
 *   }
 * };
 * // Generates: section[icon], section[images][]
 * 
 * // Example 4: Enable debug logging
 * MediaService.setDebug(true);
 */

(function() {
    'use strict';

    // Debug flag (disabled by default)
    let DEBUG = false;

    /**
     * Enable/disable debug logging
     */
    function setDebug(enabled) {
        DEBUG = enabled;
    }

    /**
     * Check if value is a File object
     */
    function isFile(value) {
        return value instanceof File || (typeof File !== 'undefined' && value instanceof File);
    }

    /**
     * Check if value is a FileList or array of Files
     */
    function isFileList(value) {
        if (!value) return false;
        
        // Check for FileList
        if (typeof FileList !== 'undefined' && value instanceof FileList) {
            return true;
        }
        
        // Check for array of Files
        if (Array.isArray(value) && value.length > 0) {
            return value.every(item => isFile(item));
        }
        
        return false;
    }

    /**
     * Check if payload contains any media fields
     */
    function hasAnyMedia(payload) {
        if (!payload || typeof payload !== 'object') return false;

        const mediaKeys = ['image', 'images', 'file', 'files', 'video', 'videos', 'icon', 'icons'];

        for (let key in payload) {
            if (payload.hasOwnProperty(key)) {
                const value = payload[key];
                
                // Check direct media keys
                if (mediaKeys.includes(key)) {
                    if (isFile(value) || isFileList(value)) {
                        return true;
                    }
                }
                
                // Check nested objects recursively
                if (value && typeof value === 'object' && !isFile(value) && !Array.isArray(value)) {
                    if (hasAnyMedia(value)) {
                        return true;
                    }
                }
                
                // Check nested arrays (e.g., media[images])
                if (Array.isArray(value) && !isFileList(value)) {
                    for (let item of value) {
                        if (isFile(item) || (item && typeof item === 'object' && hasAnyMedia(item))) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Attach media to FormData with correct key format
     */
    function attachMedia(formData, key, value, options = {}) {
        if (!value) return;

        const arrayFormat = options.arrayFormat || 'brackets'; // 'brackets' = key[], 'index' = key[0]
        const nestedBrackets = options.nestedBrackets !== false; // Default true for nested keys like media[images][]

        // Single file
        if (isFile(value)) {
            formData.append(key, value);
            if (DEBUG) console.log('[MediaService] Appended file:', key);
            return;
        }

        // FileList or array of files
        if (isFileList(value)) {
            const files = value instanceof FileList ? Array.from(value) : value;
            
            if (arrayFormat === 'brackets') {
                // Use key[] format (Laravel standard)
                const arrayKey = key.endsWith('[]') ? key : key + '[]';
                files.forEach(file => {
                    formData.append(arrayKey, file);
                    if (DEBUG) console.log('[MediaService] Appended file to array:', arrayKey);
                });
            } else {
                // Use key[0], key[1] format
                files.forEach((file, index) => {
                    formData.append(`${key}[${index}]`, file);
                    if (DEBUG) console.log('[MediaService] Appended file to array:', `${key}[${index}]`);
                });
            }
            return;
        }

        // If value is a string (existing URL), don't append as file
        if (typeof value === 'string') {
            // Skip - this is an existing URL, not a file
            if (DEBUG) console.log('[MediaService] Skipped string value (existing URL):', key);
            return;
        }

        // Array of strings (existing URLs) - skip
        if (Array.isArray(value) && value.length > 0 && typeof value[0] === 'string') {
            if (DEBUG) console.log('[MediaService] Skipped string array (existing URLs):', key);
            return;
        }
    }

    /**
     * Build FormData from payload
     */
    function buildFormData(payload, options = {}) {
        const formData = new FormData();
        
        if (!payload || typeof payload !== 'object') {
            return formData;
        }

        const arrayFormat = options.arrayFormat || 'brackets';
        const nestedBrackets = options.nestedBrackets !== false;

        function processValue(key, value, parentKey = '') {
            const fullKey = parentKey ? (nestedBrackets ? `${parentKey}[${key}]` : `${parentKey}.${key}`) : key;

            // Skip null/undefined
            if (value === null || value === undefined) {
                return;
            }

            // Handle File
            if (isFile(value)) {
                attachMedia(formData, fullKey, value, options);
                return;
            }

            // Handle FileList or array of Files
            if (isFileList(value)) {
                attachMedia(formData, fullKey, value, options);
                return;
            }

            // Handle arrays
            if (Array.isArray(value)) {
                // Check if array of files
                if (value.length > 0 && isFile(value[0])) {
                    attachMedia(formData, fullKey, value, options);
                    return;
                }

                // Array of strings (existing URLs) - append as normal fields (not files)
                if (value.length > 0 && typeof value[0] === 'string') {
                    const arrayKey = arrayFormat === 'brackets' 
                        ? (fullKey.endsWith('[]') ? fullKey : fullKey + '[]')
                        : fullKey;
                    value.forEach((item, index) => {
                        const finalKey = arrayFormat === 'brackets' ? arrayKey : `${fullKey}[${index}]`;
                        formData.append(finalKey, item);
                    });
                    if (DEBUG) console.log('[MediaService] Appended string array:', arrayKey);
                    return;
                }

                // Array of objects or primitives
                value.forEach((item, index) => {
                    if (item && typeof item === 'object' && !isFile(item)) {
                        // Recursive for nested objects in arrays
                        Object.keys(item).forEach(subKey => {
                            processValue(subKey, item[subKey], arrayFormat === 'brackets' ? `${fullKey}[]` : `${fullKey}[${index}]`);
                        });
                    } else {
                        // Primitive values in array
                        const arrayKey = arrayFormat === 'brackets' ? `${fullKey}[]` : `${fullKey}[${index}]`;
                        formData.append(arrayKey, item);
                    }
                });
                return;
            }

            // Handle objects
            if (value && typeof value === 'object') {
                Object.keys(value).forEach(subKey => {
                    processValue(subKey, value[subKey], fullKey);
                });
                return;
            }

            // Primitive values (strings, numbers, booleans)
            formData.append(fullKey, value);
        }

        // Process all keys in payload
        Object.keys(payload).forEach(key => {
            processValue(key, payload[key]);
        });

        // Log FormData keys in debug mode
        if (DEBUG) {
            console.log('[MediaService] FormData keys:', Array.from(formData.keys()));
        }

        return formData;
    }

    /**
     * Make request with media using axios or fetch
     */
    async function requestWithMedia(client, config, payload, options = {}) {
        const hasMedia = hasAnyMedia(payload);
        
        if (!hasMedia) {
            // No media, use normal JSON request
            if (client && typeof client.request === 'function') {
                // axios
                return client.request({
                    ...config,
                    data: payload,
                    headers: {
                        'Content-Type': 'application/json',
                        ...config.headers
                    }
                });
            } else {
                // fetch
                return fetch(config.url || config, {
                    method: config.method || 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...config.headers
                    },
                    body: JSON.stringify(payload)
                });
            }
        }

        // Has media, use FormData
        const formData = buildFormData(payload, options);
        
        // Handle PUT/PATCH with _method for Laravel
        const method = (config.method || 'POST').toUpperCase();
        if ((method === 'PUT' || method === 'PATCH') && options.useMethodOverride !== false) {
            formData.append('_method', method);
            if (DEBUG) console.log('[MediaService] Added _method:', method);
        }

        // Determine final HTTP method
        let finalMethod = method;
        if ((method === 'PUT' || method === 'PATCH') && options.useMethodOverride !== false) {
            finalMethod = 'POST'; // Use POST with _method override
        }

        if (client && typeof client.request === 'function') {
            // axios
            return client.request({
                ...config,
                method: finalMethod,
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data',
                    ...config.headers
                }
            });
        } else {
            // fetch
            return fetch(config.url || config, {
                method: finalMethod,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...config.headers
                    // Don't set Content-Type for FormData - browser will set it with boundary
                },
                body: formData
            });
        }
    }

    /**
     * Helper function for section API calls
     * Example usage:
     * 
     * // Using axios
     * const payload = { title: 'Section Title', images: [file1, file2], icon: file };
     * const response = await MediaService.saveSection(axios, '/api/cms/admin/sections', payload);
     * 
     * // Using fetch
     * const response = await MediaService.saveSection(fetch, '/api/cms/admin/sections', payload);
     * 
     * // Update section
     * const response = await MediaService.updateSection(axios, '/api/cms/admin/sections/1', payload);
     */
    async function saveSection(client, url, payload, options = {}) {
        return requestWithMedia(client, {
            url: url,
            method: 'POST',
            ...options
        }, payload, options);
    }

    async function updateSection(client, url, payload, options = {}) {
        return requestWithMedia(client, {
            url: url,
            method: 'PUT',
            ...options
        }, payload, options);
    }

    // Export functions
    if (typeof module !== 'undefined' && module.exports) {
        // Node.js
        module.exports = {
            isFile,
            isFileList,
            hasAnyMedia,
            buildFormData,
            attachMedia,
            requestWithMedia,
            setDebug,
            saveSection,
            updateSection
        };
    } else {
        // Browser global
        window.MediaService = {
            isFile,
            isFileList,
            hasAnyMedia,
            buildFormData,
            attachMedia,
            requestWithMedia,
            setDebug,
            saveSection,
            updateSection
        };
    }

})();

