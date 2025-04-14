<x-client-layout>
    <x-slot:header>Place New Order</x-slot:header>
    
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <form action="{{ route('client.orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-8 border-b border-gray-200 pb-4 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                
                <div class="mb-6 grid gap-6 md:grid-cols-2">
                    <!-- Paper Title -->
                    <div class="col-span-2">
                        <label for="title" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Paper Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Enter the title of your paper" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Academic Level -->
                    <div>
                        <label for="academic_level" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Academic Level <span class="text-red-500">*</span></label>
                        <select id="academic_level" name="academic_level" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="">Select Level</option>
                            <option value="high_school" {{ old('academic_level') == 'high_school' ? 'selected' : '' }}>High School</option>
                            <option value="undergraduate" {{ old('academic_level') == 'undergraduate' ? 'selected' : '' }}>Undergraduate (Bachelor)</option>
                            <option value="masters" {{ old('academic_level') == 'masters' ? 'selected' : '' }}>Master's</option>
                            <option value="doctoral" {{ old('academic_level') == 'doctoral' ? 'selected' : '' }}>Doctoral (PhD)</option>
                        </select>
                        @error('academic_level')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Subject Area -->
                    <div>
                        <label for="subject_area" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Subject Area <span class="text-red-500">*</span></label>
                        <select id="subject_area" name="subject_area" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="">Select Subject</option>
                            <option value="business" {{ old('subject_area') == 'business' ? 'selected' : '' }}>Business & Management</option>
                            <option value="engineering" {{ old('subject_area') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="nursing" {{ old('subject_area') == 'nursing' ? 'selected' : '' }}>Nursing & Healthcare</option>
                            <option value="literature" {{ old('subject_area') == 'literature' ? 'selected' : '' }}>Literature</option>
                            <option value="psychology" {{ old('subject_area') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                            <option value="sociology" {{ old('subject_area') == 'sociology' ? 'selected' : '' }}>Sociology</option>
                            <option value="history" {{ old('subject_area') == 'history' ? 'selected' : '' }}>History</option>
                            <option value="computer_science" {{ old('subject_area') == 'computer_science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="other" {{ old('subject_area') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject_area')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Page Count -->
                    <div>
                        <label for="word_count" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Number of Pages <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <button type="button" id="decrementPages" class="rounded-l-lg border border-gray-300 bg-gray-100 px-3 py-2.5 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">-</button>
                            <input type="number" id="page_count" name="page_count" value="{{ old('page_count', 1) }}" min="1" max="100" class="block w-full border-y border-gray-300 bg-gray-50 py-2.5 text-center text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <button type="button" id="incrementPages" class="rounded-r-lg border border-gray-300 bg-gray-100 px-3 py-2.5 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">+</button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Approximately <span id="wordCount">275</span> words</p>
                        <input type="hidden" id="word_count" name="word_count" value="275">
                        @error('word_count')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Deadline -->
                    <div>
                        <label for="deadline" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deadline <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Citation Style -->
                    <div>
                        <label for="citation_style" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Citation Style <span class="text-red-500">*</span></label>
                        <select id="citation_style" name="citation_style" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="">Select Style</option>
                            <option value="apa" {{ old('citation_style') == 'apa' ? 'selected' : '' }}>APA</option>
                            <option value="mla" {{ old('citation_style') == 'mla' ? 'selected' : '' }}>MLA</option>
                            <option value="chicago" {{ old('citation_style') == 'chicago' ? 'selected' : '' }}>Chicago/Turabian</option>
                            <option value="harvard" {{ old('citation_style') == 'harvard' ? 'selected' : '' }}>Harvard</option>
                            <option value="other" {{ old('citation_style') == 'other' ? 'selected' : '' }}>Other (specify in instructions)</option>
                        </select>
                        @error('citation_style')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Sources Required -->
                    <div>
                        <label for="sources_required" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Number of Sources Required</label>
                        <input type="number" id="sources_required" name="sources_required" value="{{ old('sources_required', 0) }}" min="0" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        @error('sources_required')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Detailed Instructions -->
                <div class="mb-6">
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Detailed Instructions <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="6" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Provide detailed instructions, requirements, and any specific guidelines for the writer...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Supporting Files -->
                <div class="mb-6">
                    <label for="files" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Supporting Files (Optional)</label>
                    <input type="file" id="files" name="files[]" multiple class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload any relevant materials (Max. 10MB per file)</p>
                    @error('files')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Service Options -->
            <div class="mb-8">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Additional Services</h3>
                
                <div class="mb-6 space-y-4">
                    <div class="flex items-center">
                        <input id="plagiarism_report" type="checkbox" name="services[]" value="plagiarism_report" {{ in_array('plagiarism_report', old('services', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                        <label for="plagiarism_report" class="ml-2 flex cursor-pointer items-center text-sm font-medium text-gray-900 dark:text-gray-300">
                            <span class="mr-6">Plagiarism Report</span>
                            <span class="text-blue-700 dark:text-blue-400">+$9.99</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="top_writer" type="checkbox" name="services[]" value="top_writer" {{ in_array('top_writer', old('services', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                        <label for="top_writer" class="ml-2 flex cursor-pointer items-center text-sm font-medium text-gray-900 dark:text-gray-300">
                            <span class="mr-6">Top Writer (Ph.D. level expertise)</span>
                            <span class="text-blue-700 dark:text-blue-400">+30%</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="expedited_delivery" type="checkbox" name="services[]" value="expedited_delivery" {{ in_array('expedited_delivery', old('services', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                        <label for="expedited_delivery" class="ml-2 flex cursor-pointer items-center text-sm font-medium text-gray-900 dark:text-gray-300">
                            <span class="mr-6">Expedited Delivery (24h priority)</span>
                            <span class="text-blue-700 dark:text-blue-400">+25%</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Price Summary -->
            <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h3>
                <div class="mb-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Base Price:</span>
                        <span class="font-medium text-gray-900 dark:text-white">$<span id="basePrice">0.00</span></span>
                    </div>
                    <div id="additionalServices" class="hidden space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-300">Additional Services:</span>
                            <span class="font-medium text-gray-900 dark:text-white">$<span id="additionalPrice">0.00</span></span>
                        </div>
                        <div id="servicesList" class="ml-4 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <!-- Dynamically populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-3 dark:border-gray-700">
                    <span class="text-lg font-bold text-gray-900 dark:text-white">Total:</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">$<span id="totalPrice">0.00</span></span>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="mb-8">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Payment Method</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input id="payment_credit_card" type="radio" name="payment_method" value="credit_card" {{ old('payment_method') == 'credit_card' ? 'checked' : 'checked' }} class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                        <label for="payment_credit_card" class="ml-2 flex cursor-pointer items-center text-sm font-medium text-gray-900 dark:text-gray-300">
                            <svg class="mr-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Credit Card
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="payment_paypal" type="radio" name="payment_method" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }} class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                        <label for="payment_paypal" class="ml-2 flex cursor-pointer items-center text-sm font-medium text-gray-900 dark:text-gray-300">
                            <svg class="mr-2 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.217a.785.785 0 0 1 .775-.654h6.261c2.448 0 4.142.906 4.651 2.498.524 1.599.029 3.339-1.335 4.725 1.42.513 2.439 1.683 2.626 3.32.031.3.066.603.066.906-.354 3.605-3.359 5.055-6.796 5.055h-.739c-.44 0-.812.323-.876.751l-.245 1.519zm8.274-8.245c-.006-.035-.018-.07-.035-.106l-.026-.054a.212.212 0 0 1-.007-.035c-.011-.077-.032-.154-.062-.231a3.134 3.134 0 0 0-.801-1.23c-.656-.657-1.636-.978-2.913-.978h-4.15c-.241 0-.437.19-.467.425L5.982 18.5h4.254c.998 0 1.97-.333 2.617-.903.61-.535 1.018-1.263 1.194-2.138.013-.068.024-.136.034-.205.04-.296.052-.605.035-.91.235.126.35.233.39.322a.42.42 0 0 1 .034.424c-.4.092-.109.152-.19.169 0 .019-.006.037-.006.056v.038c-.006.068-.006.137.006.205a.689.689 0 0 1-.109.424.955.955 0 0 1-.073.13c-.7.091-.21.323-.438.657-.345.5-.738.852-1.225 1.039-.488.194-1.069.291-1.742.291H7.266c-.19 0-.346.152-.365.337L6.5 21.027h3.62c.329 0 .608-.245.66-.566l.28-1.722c.058-.352.357-.61.714-.61h.782c3.421 0 5.44-1.723 5.474-4.682a4.33 4.33 0 0 0-.03-.6c-.006-.17-.024-.337-.048-.498a.431.431 0 0 1-.029-.068 2.342 2.342 0 0 0-.22-.554l-.005-.01z" />
                                <path d="M12.514 10.823c-1.32 0-3.498-.304-3.498-2.65 0-1.167.905-1.858 2.342-1.858h3.179c.19 0 .346-.152.37-.342l.232-1.458a.367.367 0 0 0-.364-.425H10.68c-2.753 0-4.683 1.844-4.683 4.488 0 2.307 1.438 3.61 3.97 3.61.07 0 .137.005.204.01h.132c2.159 0 3.661-.83 3.661-2.06 0-.688-.508-1.079-1.45-1.313z" />
                            </svg>
                            PayPal
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800 sm:w-auto">
                Proceed to Payment
            </button>
        </form>
    </div>

    <script>
        // Calculate price
        const pageCountInput = document.getElementById('page_count');
        const wordCountSpan = document.getElementById('wordCount');
        const wordCountInput = document.getElementById('word_count');
        const basePriceSpan = document.getElementById('basePrice');
        const additionalPriceSpan = document.getElementById('additionalPrice');
        const totalPriceSpan = document.getElementById('totalPrice');
        const additionalServices = document.getElementById('additionalServices');
        const servicesList = document.getElementById('servicesList');
        
        // Page count controls
        document.getElementById('decrementPages').addEventListener('click', function() {
            if (parseInt(pageCountInput.value) > 1) {
                pageCountInput.value = parseInt(pageCountInput.value) - 1;
                updateWordCount();
                calculatePrice();
            }
        });
        
        document.getElementById('incrementPages').addEventListener('click', function() {
            pageCountInput.value = parseInt(pageCountInput.value) + 1;
            updateWordCount();
            calculatePrice();
        });
        
        // Update word count when page count changes
        pageCountInput.addEventListener('change', function() {
            updateWordCount();
            calculatePrice();
        });

        // Additional services checkboxes
        const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]');
        serviceCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculatePrice);
        });
        
        // Academic level and deadline affect pricing
        document.getElementById('academic_level').addEventListener('change', calculatePrice);
        document.getElementById('deadline').addEventListener('change', calculatePrice);
        
        function updateWordCount() {
            const pageCount = parseInt(pageCountInput.value);
            const wordsPerPage = 275;
            const totalWords = pageCount * wordsPerPage;
            wordCountSpan.textContent = totalWords;
            wordCountInput.value = totalWords; // Update the hidden input
        }
        
        function calculatePrice() {
            // Base pricing per page by academic level
            const basePricePerPage = {
                'high_school': 10.99,
                'undergraduate': 14.99,
                'masters': 19.99,
                'doctoral': 24.99
            };
            
            const academicLevel = document.getElementById('academic_level').value || 'undergraduate';
            const pageCount = parseInt(pageCountInput.value);
            
            // Calculate base price
            let basePrice = (basePricePerPage[academicLevel] || 14.99) * pageCount;
            
            // Apply urgency factor based on deadline
            const deadline = new Date(document.getElementById('deadline').value);
            const now = new Date();
            const diffTime = Math.abs(deadline - now);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays <= 1) {
                basePrice *= 1.5; // 50% urgency fee for 24 hours or less
            } else if (diffDays <= 3) {
                basePrice *= 1.3; // 30% urgency fee for 2-3 days
            } else if (diffDays <= 7) {
                basePrice *= 1.15; // 15% urgency fee for 4-7 days
            }
            
            // Calculate additional services
            let additionalPrice = 0;
            let servicesText = '';
            
            serviceCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    if (checkbox.value === 'plagiarism_report') {
                        additionalPrice += 9.99;
                        servicesText += '<div>Plagiarism Report: +$9.99</div>';
                    } else if (checkbox.value === 'top_writer') {
                        const topWriterFee = basePrice * 0.3;
                        additionalPrice += topWriterFee;
                        servicesText += `<div>Top Writer: +$${topWriterFee.toFixed(2)}</div>`;
                    } else if (checkbox.value === 'expedited_delivery') {
                        const expeditedFee = basePrice * 0.25;
                        additionalPrice += expeditedFee;
                        servicesText += `<div>Expedited Delivery: +$${expeditedFee.toFixed(2)}</div>`;
                    }
                }
            });
            
            // Update UI
            basePriceSpan.textContent = basePrice.toFixed(2);
            additionalPriceSpan.textContent = additionalPrice.toFixed(2);
            totalPriceSpan.textContent = (basePrice + additionalPrice).toFixed(2);
            
            if (additionalPrice > 0) {
                additionalServices.classList.remove('hidden');
                servicesList.innerHTML = servicesText;
            } else {
                additionalServices.classList.add('hidden');
                servicesList.innerHTML = '';
            }
        }
        
        // Initialize calculations
        updateWordCount();
        calculatePrice();
    </script>
</x-client-layout> 