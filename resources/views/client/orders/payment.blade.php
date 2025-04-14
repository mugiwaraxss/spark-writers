<x-client-layout>
    <x-slot:header>Payment for Order #{{ $order->order_number }}</x-slot:header>

    <div class="mb-8">
        <a href="{{ route('client.orders.show', $order) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Order
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-300" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-8 md:grid-cols-3">
        <!-- Order Summary -->
        <div class="md:col-span-1">
            <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-base font-medium text-gray-500 dark:text-gray-400">Order #</span>
                        <span class="text-base font-medium text-gray-900 dark:text-white">{{ $order->id }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-base font-medium text-gray-500 dark:text-gray-400">Title</span>
                        <span class="text-right text-base font-medium text-gray-900 dark:text-white">{{ Str::limit($order->title, 30) }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-base font-medium text-gray-500 dark:text-gray-400">Academic Level</span>
                        <span class="text-base font-medium text-gray-900 dark:text-white">{{ $order->academic_level }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-base font-medium text-gray-500 dark:text-gray-400">Words</span>
                        <span class="text-base font-medium text-gray-900 dark:text-white">{{ number_format($order->word_count) }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                        <span class="text-base font-medium text-gray-500 dark:text-gray-400">Deadline</span>
                        <span class="text-base font-medium text-gray-900 dark:text-white">
                            @if($order->deadline instanceof \Carbon\Carbon)
                                {{ $order->deadline->format('M d, Y h:i A') }}
                            @else
                                {{ \Carbon\Carbon::parse($order->deadline)->format('M d, Y h:i A') }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total Amount</span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-500">${{ number_format($order->payment->amount, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Need Help?</h3>
                <p class="mb-4 text-gray-600 dark:text-gray-400">If you're having trouble with your payment or have questions about our payment process, please don't hesitate to contact our support team.</p>
                <a href="#" class="inline-flex items-center text-blue-600 hover:underline dark:text-blue-500">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    Contact Support
                </a>
            </div>
        </div>
        
        <!-- Payment Form -->
        <div class="md:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">Complete Your Payment</h3>
                
                <form action="{{ route('client.orders.process-payment', $order) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">Select your preferred payment method:</p>
                        
                        <div class="mb-4 grid gap-4 md:grid-cols-3">
                            <div>
                                <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="peer hidden" checked>
                                <label for="credit_card" class="flex cursor-pointer flex-col items-center justify-between rounded-lg border-2 border-gray-200 bg-white p-4 text-gray-500 hover:border-gray-300 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:peer-checked:text-blue-500">
                                    <svg class="mb-2 h-7 w-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="w-full text-center font-semibold">Credit Card</div>
                                </label>
                            </div>
                            
                            <div>
                                <input type="radio" name="payment_method" id="paypal" value="paypal" class="peer hidden">
                                <label for="paypal" class="flex cursor-pointer flex-col items-center justify-between rounded-lg border-2 border-gray-200 bg-white p-4 text-gray-500 hover:border-gray-300 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:peer-checked:text-blue-500">
                                    <svg class="mb-2 h-7 w-7" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.217a.785.785 0 0 1 .775-.654h6.261c2.448 0 4.142.906 4.651 2.498.524 1.599.029 3.339-1.335 4.725 1.42.513 2.439 1.683 2.626 3.32.031.3.066.603.066.906-.354 3.605-3.359 5.055-6.796 5.055h-.739a.674.674 0 0 0-.665.588l-.321 2.01a.606.606 0 0 1-.597.512.659.659 0 0 1-.11-.01l-.3.016zm9.045-8.445c-.006-.035-.018-.07-.035-.106l-.026-.054a.212.212 0 0 1-.007-.035c-.011-.077-.032-.154-.062-.231a3.134 3.134 0 0 0-.801-1.23c-.656-.657-1.636-.978-2.913-.978h-4.15c-.241 0-.437.19-.467.425L6.753 18.2h4.117c.64 0 2.516 0 3.575-1.084.959-.981 1.17-2.425.878-3.877-.13-.075-.118-.141-.089-.233l-.004-.018a.056.056 0 0 1-.009-.027c-.003-.01-.009-.021-.012-.031a.384.384 0 0 1-.01-.038z" />
                                    </svg>
                                    <div class="w-full text-center font-semibold">PayPal</div>
                                </label>
                            </div>
                            
                            <div>
                                <input type="radio" name="payment_method" id="mpesa" value="mpesa" class="peer hidden">
                                <label for="mpesa" class="flex cursor-pointer flex-col items-center justify-between rounded-lg border-2 border-gray-200 bg-white p-4 text-gray-500 hover:border-gray-300 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:peer-checked:text-blue-500">
                                    <svg class="mb-2 h-7 w-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.999 17.129a1.974 1.974 0 0 1-1.97 1.971H7.97A1.973 1.973 0 0 1 6 17.129V6.871C6 5.785 6.879 4.9 7.97 4.9h8.06c1.09 0 1.97.884 1.97 1.971v10.258zM15.595 12a3.6 3.6 0 0 0-3.594-3.604A3.6 3.6 0 0 0 8.406 12a3.6 3.6 0 0 0 3.595 3.604A3.6 3.6 0 0 0 15.595 12z" />
                                    </svg>
                                    <div class="w-full text-center font-semibold">M-Pesa</div>
                                </label>
                            </div>
                        </div>
                        
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div id="payment_details" class="mb-6 space-y-4">
                        <div class="credit_card_details">
                            <!-- Credit Card Fields -->
                            <div class="mb-6 grid gap-4 md:grid-cols-2">
                                <div class="col-span-2">
                                    <label for="card_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Card Number</label>
                                    <input type="text" id="card_number" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="1234 5678 9012 3456">
                                </div>
                                
                                <div>
                                    <label for="expiry" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Expiry Date</label>
                                    <input type="text" id="expiry" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="MM/YY">
                                </div>
                                
                                <div>
                                    <label for="cvv" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">CVV</label>
                                    <input type="text" id="cvv" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="123">
                                </div>
                            </div>
                        </div>
                        
                        <div class="paypal_details hidden">
                            <p class="mb-4 rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                You will be redirected to PayPal to complete your payment securely.
                            </p>
                        </div>
                        
                        <div class="mpesa_details hidden">
                            <p class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300">
                                Enter your M-Pesa phone number below. You will receive a prompt to complete payment.
                            </p>
                            
                            <div>
                                <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                                <input type="text" id="phone_number" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="+254 7XX XXX XXX">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="transaction_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Transaction ID</label>
                        <input type="text" id="transaction_id" name="transaction_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Enter transaction ID" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">For demo purposes, enter any ID. In a real application, this would be provided by the payment processor.</p>
                        @error('transaction_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                            Complete Payment (${!! number_format($order->payment->amount, 2) !!})
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const paymentDetails = {
                credit_card: document.querySelector('.credit_card_details'),
                paypal: document.querySelector('.paypal_details'),
                mpesa: document.querySelector('.mpesa_details')
            };
            
            function togglePaymentDetails() {
                // Hide all payment details sections
                Object.values(paymentDetails).forEach(el => {
                    if (el) el.classList.add('hidden');
                });
                
                // Show the selected payment method details
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
                if (paymentDetails[selectedMethod]) {
                    paymentDetails[selectedMethod].classList.remove('hidden');
                }
            }
            
            // Add event listeners to payment method radio buttons
            paymentMethods.forEach(radio => {
                radio.addEventListener('change', togglePaymentDetails);
            });
            
            // Initialize display based on default selection
            togglePaymentDetails();
        });
    </script>
</x-client-layout> 