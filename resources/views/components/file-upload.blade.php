@props([
    'name' => 'file',
    'label' => 'Upload File',
    'multiple' => false,
    'accept' => '*',
    'required' => false,
    'help' => null,
    'existingFiles' => [],
    'maxSizeMB' => 10,
    'allowedExtensions' => null
])

<div class="file-upload-component">
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
        {{ $label }} {{ $required ? '<span class="text-red-500">*</span>' : '' }}
    </label>
    
    <div 
        x-data="{ 
            filesToUpload: [], 
            existingFiles: @js($existingFiles),
            isMultiple: {{ $multiple ? 'true' : 'false' }},
            maxSize: {{ $maxSizeMB * 1024 * 1024 }},
            allowedExtensions: @js($allowedExtensions ? explode(',', $allowedExtensions) : null),
            dragOver: false,
            fileInputId: '{{ $name }}',

            init() {
                this.$watch('filesToUpload', value => {
                    if (!this.isMultiple && value.length > 1) {
                        this.filesToUpload = [value[0]];
                    }
                });
            },

            handleFiles(fileList) {
                const files = Array.from(fileList);
                
                // Validate files
                const validFiles = files.filter(file => {
                    // Check size
                    if (file.size > this.maxSize) {
                        alert(`File '${file.name}' exceeds the maximum file size of ${this.maxSizeMB}MB.`);
                        return false;
                    }
                    
                    // Check extension if specified
                    if (this.allowedExtensions) {
                        const extension = file.name.split('.').pop().toLowerCase();
                        if (!this.allowedExtensions.includes(extension)) {
                            alert(`File '${file.name}' has an invalid file type. Allowed types: ${this.allowedExtensions.join(', ')}`);
                            return false;
                        }
                    }
                    
                    return true;
                });
                
                if (this.isMultiple) {
                    this.filesToUpload = [...this.filesToUpload, ...validFiles];
                } else {
                    this.filesToUpload = validFiles.slice(0, 1);
                    // Update the actual file input for single files
                    if (validFiles.length > 0) {
                        const dt = new DataTransfer();
                        dt.items.add(validFiles[0]);
                        document.getElementById(this.fileInputId).files = dt.files;
                    }
                }
            },

            removeFile(index) {
                this.filesToUpload.splice(index, 1);
                
                // Update the actual file input after removal
                const dt = new DataTransfer();
                this.filesToUpload.forEach(file => dt.items.add(file));
                document.getElementById(this.fileInputId).files = dt.files;
            },

            removeExistingFile(index) {
                this.existingFiles.splice(index, 1);
            },

            formatFileSize(bytes) {
                if (bytes < 1024) {
                    return bytes + ' bytes';
                } else if (bytes < 1024 * 1024) {
                    return (bytes / 1024).toFixed(2) + ' KB';
                } else {
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                }
            },

            getFileIcon(filename) {
                const extension = filename.split('.').pop().toLowerCase();
                
                const icons = {
                    'pdf': '<svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                    'doc': '<svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'docx': '<svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'xls': '<svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'xlsx': '<svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'ppt': '<svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'pptx': '<svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    'jpg': '<svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    'jpeg': '<svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    'png': '<svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    'zip': '<svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>',
                    'txt': '<svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
                };
                
                return icons[extension] || '<svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
            }
        }"
        
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="dragOver = false; handleFiles($event.dataTransfer.files)"
    >
        <!-- File Input (Hidden but functional) -->
        <input type="file" id="{{ $name }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" class="hidden" 
            {{ $multiple ? 'multiple' : '' }} 
            accept="{{ $accept }}" 
            {{ $required ? 'required' : '' }}
            @change="handleFiles($event.target.files)"
        />

        <!-- Dropzone Area -->
        <label for="{{ $name }}" class="flex h-32 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed p-6 transition"
            :class="dragOver 
                ? 'border-blue-500 bg-blue-50 dark:border-blue-400 dark:bg-blue-900/30' 
                : 'border-gray-300 bg-gray-50 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600'"
        >
            <div class="flex flex-col items-center justify-center text-center">
                <svg class="mb-3 h-10 w-10 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $help ?? 'Maximum file size: ' . $maxSizeMB . 'MB' }}
                    @if($allowedExtensions)
                    <br>Allowed file types: {{ $allowedExtensions }}
                    @endif
                </p>
            </div>
        </label>

        <!-- File Preview Area -->
        <div class="mt-4 space-y-3">
            <!-- Existing Files (if provided) -->
            <template x-if="existingFiles.length > 0">
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Current Files:</h4>
                    <ul class="space-y-2">
                        <template x-for="(file, index) in existingFiles" :key="index">
                            <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center">
                                    <span x-html="getFileIcon(file.name)"></span>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatFileSize(file.size)"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a :href="file.url" target="_blank" class="rounded-full p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" @click="removeExistingFile(index)" class="rounded-full p-1 text-red-500 hover:bg-red-100 dark:hover:bg-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- Newly Selected Files -->
            <template x-if="filesToUpload.length > 0">
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Selected Files:</h4>
                    <ul class="space-y-2">
                        <template x-for="(file, index) in filesToUpload" :key="index">
                            <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center">
                                    <span x-html="getFileIcon(file.name)"></span>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatFileSize(file.size)"></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeFile(index)" class="rounded-full p-1 text-red-500 hover:bg-red-100 dark:hover:bg-red-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- Track deleted files -->
            <template x-for="(file, index) in existingFiles" :key="'deleted-'+index">
                <template x-if="file.id && file._deleted">
                    <input type="hidden" :name="`deleted_${$name}[]`" :value="file.id" />
                </template>
            </template>
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div> 