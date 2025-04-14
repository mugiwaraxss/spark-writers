@props([
    'amount',
    'description',
    'processingUrl', 
    'cancelUrl',
    'successUrl',
    'paymentMethods' => ['card', 'paypal'],
    'clientSecret' => null
])

<div
    x-data="{
        paymentMethod: 'card',
        isProcessing: false,
        errorMessage: '',
        stripe: null,
        elements: null,
        card: null,
        
        async init() {
            if (!window.Stripe) {
                console.error('Stripe.js not loaded');
                return;
            }
            
            // Initialize Stripe and Elements
            if (this.paymentMethod === 'card' && '{{ $clientSecret }}') {
                this.stripe = Stripe('{{ config('services.stripe.key') }}');
                this.elements = this.stripe.elements({
                    clientSecret: '{{ $clientSecret }}'
                });
                
                // Create card element
                this.card = this.elements.create('card', {
                    style: {
                        base: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#32325d',
                            fontFamily: '"Inter", ui-sans-serif, system-ui, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#aab7c4'
                            },
                            backgroundColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    }
                });
                
                // Mount the card element
                this.$nextTick(() => {
                    this.card.mount('#card-element');
                    
                    // Listen for changes and errors
                    this.card.on('change', (event) => {
                        this.errorMessage = event.error ? event.error.message : '';
                    });
                });
            }
        },
        
        async processCardPayment() {
            if (!this.stripe || !this.card) {
                this.errorMessage = 'Payment processing is not available';
                return;
            }
            
            this.isProcessing = true;
            this.errorMessage = '';
            
            try {
                const { error } = await this.stripe.confirmCardPayment('{{ $clientSecret }}', {
                    payment_method: {
                        card: this.card,
                        billing_details: {
                            name: document.getElementById('cardholder-name').value
                        }
                    }
                });
                
                if (error) {
                    this.errorMessage = error.message;
                    this.isProcessing = false;
                } else {
                    // Payment succeeded, redirect to success page
                    window.location.href = '{{ $successUrl }}';
                }
            } catch (e) {
                this.errorMessage = 'An unexpected error occurred.';
                this.isProcessing = false;
                console.error(e);
            }
        },
        
        async processPayment() {
            if (this.isProcessing) return;
            
            if (this.paymentMethod === 'card') {
                await this.processCardPayment();
            } else if (this.paymentMethod === 'paypal') {
                // For PayPal, we'll submit the form to the server which will redirect to PayPal
                document.getElementById('payment-form').submit();
            }
        }
    }"
    @payment-method-changed.window="paymentMethod = $event.detail; init()"
>
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-6">
            <h3 class="mb-1 text-xl font-semibold text-gray-900 dark:text-white">Payment Details</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Complete your purchase securely</p>
        </div>
        
        <div class="mb-6">
            <div class="mb-2 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Amount:</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($amount, 2) }}</p>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
        </div>
        
        <!-- Payment Method Selector -->
        <div class="mb-6">
            <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</p>
            <div class="flex space-x-4">
                @if(in_array('card', $paymentMethods))
                <button 
                    type="button" 
                    @click="paymentMethod = 'card'; init()"
                    class="flex items-center rounded-lg border p-4 transition"
                    :class="paymentMethod === 'card' ? 'border-blue-500 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/30' : 'border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50'"
                >
                    <svg class="mr-3 h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Credit Card</span>
                </button>
                @endif
                
                @if(in_array('paypal', $paymentMethods))
                <button 
                    type="button" 
                    @click="paymentMethod = 'paypal'; init()"
                    class="flex items-center rounded-lg border p-4 transition"
                    :class="paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/30' : 'border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50'"
                >
                    <svg class="mr-3 h-6 w-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.217a.785.785 0 0 1 .775-.654h6.261c2.448 0 4.142.906 4.651 2.498.524 1.599.029 3.339-1.335 4.725 1.42.513 2.439 1.683 2.626 3.32.031.3.066.603.066.906-.354 3.605-3.359 5.055-6.796 5.055h-.739c-.44 0-.812.323-.876.751l-.245 1.519zm8.274-8.245c-.006-.035-.018-.07-.035-.106l-.026-.054a.212.212 0 0 1-.007-.035c-.011-.077-.032-.154-.062-.231a3.134 3.134 0 0 0-.801-1.23c-.656-.657-1.636-.978-2.913-.978h-4.15c-.241 0-.437.19-.467.425L5.982 18.5h4.254c.998 0 1.97-.333 2.617-.903.61-.535 1.018-1.263 1.194-2.138.013-.068.024-.136.034-.205.04-.296.052-.605.035-.91.235.126.35.233.39.322a.42.42 0 0 1 .034.424c-.4.092-.109.152-.19.169 0 .019-.006.037-.006.056v.038c-.006.068-.006.137.006.205a.689.689 0 0 1-.109.424.955.955 0 0 1-.073.13c-.7.091-.21.323-.438.657-.345.5-.738.852-1.225 1.039-.488.194-1.069.291-1.742.291H7.266c-.19 0-.346.152-.365.337L6.5 21.027h3.62c.329 0 .608-.245.66-.566l.28-1.722c.058-.352.357-.61.714-.61h.782c3.421 0 5.44-1.723 5.474-4.682a4.33 4.33 0 0 0-.03-.6c-.006-.17-.024-.337-.048-.498a.431.431 0 0 1-.029-.068 2.342 2.342 0 0 0-.22-.554l-.005-.01z" />
                        <path d="M12.514 10.823c-1.32 0-3.498-.304-3.498-2.65 0-1.167.905-1.858 2.342-1.858h3.179c.19 0 .346-.152.37-.342l.232-1.458a.367.367 0 0 0-.364-.425H10.68c-2.753 0-4.683 1.844-4.683 4.488 0 2.307 1.438 3.61 3.97 3.61.07 0 .137.005.204.01h.132c2.159 0 3.661-.83 3.661-2.06 0-.688-.508-1.079-1.45-1.313z" />
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">PayPal</span>
                </button>
                @endif
            </div>
        </div>
        
        <!-- Card Payment Form -->
        <div x-show="paymentMethod === 'card'" x-transition>
            <div class="mb-4">
                <label for="cardholder-name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Cardholder Name</label>
                <input type="text" id="cardholder-name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Name on card" required>
            </div>
            
            <div class="mb-6">
                <label for="card-element" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Card Details</label>
                <div id="card-element" class="rounded-lg border border-gray-300 bg-gray-50 p-3.5 dark:border-gray-600 dark:bg-gray-700">
                    <!-- Stripe Element will be mounted here -->
                </div>
                <div x-show="errorMessage" class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="errorMessage"></div>
            </div>
        </div>
        
        <!-- PayPal Payment Form -->
        <div x-show="paymentMethod === 'paypal'" x-transition>
            <form id="payment-form" action="{{ $processingUrl }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" value="paypal">
                <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                    You will be redirected to PayPal to complete your payment. After payment, you'll return to our site.
                </p>
            </form>
        </div>
        
        <!-- Payment Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ $cancelUrl }}" class="text-sm font-medium text-gray-700 transition hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Cancel</a>
            
            <button 
                type="button" 
                @click="processPayment"
                class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800"
                :disabled="isProcessing"
            >
                <template x-if="isProcessing">
                    <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="paymentMethod === 'card' ? 'Pay ${{ number_format($amount, 2) }}' : 'Continue to PayPal'"></span>
            </button>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script src="https://js.stripe.com/v3/"></script>
@endPushOnce 