<x-layouts.client>
    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-star text-primary-500 mr-2" style="color: #3b82f6;"></i>
                    Write a Review
                </h1>
                <a href="{{ route('client.orders.show', $order) }}" class="flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 dark:bg-primary-600 dark:hover:bg-primary-700 !important" style="background-color: #2563eb; color: white;">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Order
                </a>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-md transition-all duration-300 hover:shadow-lg dark:bg-gray-800">
                <div class="p-6">
                    <div class="mb-6 animate-slide-in">
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-info-circle text-primary-500 mr-2"></i>
                            Order Information
                        </h2>
                        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-lg bg-gray-50 p-4 transition-all duration-300 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Order ID</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number ?? $order->id }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-4 transition-all duration-300 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Writer</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->writer->name }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-4 transition-all duration-300 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Title</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->title }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-4 transition-all duration-300 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Completion Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $order->completed_at ? $order->completed_at->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('client.reviews.store', $order) }}" method="POST" class="animate-fade-in" style="animation-delay: 0.2s">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="rating" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                <i class="fas fa-star text-primary-500 mr-2"></i>
                                Rating
                            </label>
                            <div class="flex items-center space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                <div class="transition-transform hover:scale-110">
                                    <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" class="peer hidden" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="rating-{{ $i }}" class="cursor-pointer text-4xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition-all duration-300" style="color: #d1d5db; font-size: 2.5rem;">★</label>
                                </div>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="comment" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                <i class="fas fa-comment text-primary-500 mr-2"></i>
                                Your Review
                            </label>
                            <textarea 
                                id="comment" 
                                name="comment" 
                                rows="5" 
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 transition-all duration-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder="Share your experience working with this writer..."
                                required
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-primary-500 mr-2"></i>
                                Your review will be visible to the writer and other clients.
                            </p>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn-hover-effect relative inline-flex items-center overflow-hidden rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 !important" style="background-color: #2563eb; color: white;">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Review
                                <span class="absolute right-0 top-0 h-full w-12 translate-x-12 bg-white opacity-20 transition-transform duration-1000 group-hover:-translate-x-40"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Star rating hover effect with smoother animation
            const stars = document.querySelectorAll('label[for^="rating-"]');
            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => {
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add('text-yellow-400');
                        stars[i].classList.remove('text-gray-300');
                        stars[i].style.color = '#facc15'; // Yellow-400 color
                    }
                    for (let i = index + 1; i < stars.length; i++) {
                        if (!document.getElementById(`rating-${i+1}`).checked) {
                            stars[i].classList.add('text-gray-300');
                            stars[i].classList.remove('text-yellow-400');
                            stars[i].style.color = '#d1d5db'; // Gray-300 color
                        }
                    }
                });
                
                star.addEventListener('mouseout', () => {
                    stars.forEach((s, i) => {
                        if (document.getElementById(`rating-${i+1}`).checked) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                            s.style.color = '#facc15'; // Yellow-400 color
                        } else {
                            s.classList.add('text-gray-300');
                            s.classList.remove('text-yellow-400');
                            s.style.color = '#d1d5db'; // Gray-300 color
                        }
                    });
                });
                
                // Add small bounce animation on hover
                star.addEventListener('mouseenter', function() {
                    this.classList.add('animate-bounce-small');
                    this.style.transform = 'scale(1.1)';
                });
                
                star.addEventListener('mouseleave', function() {
                    this.classList.remove('animate-bounce-small');
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</x-layouts.client> 