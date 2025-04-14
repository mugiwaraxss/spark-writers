<x-admin-layout title="Settings">
    <x-slot name="header">Platform Settings</x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <!-- General Settings -->
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">General Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" name="site_name" id="site_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('app.name') }}">
                    </div>
                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ config('app.description') }}</textarea>
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('mail.from.address') }}">
                    </div>
                    <button type="submit"
                        class="inline-flex justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Payment Settings -->
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Payment Settings</h2>
            <form action="{{ route('admin.settings.payment.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="writer_commission" class="block text-sm font-medium text-gray-700">Writer Commission (%)</label>
                        <input type="number" name="writer_commission" id="writer_commission" required min="0" max="100"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('settings.writer_commission', 70) }}">
                    </div>
                    <div>
                        <label for="minimum_payout" class="block text-sm font-medium text-gray-700">Minimum Payout Amount ($)</label>
                        <input type="number" name="minimum_payout" id="minimum_payout" required min="0" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('settings.minimum_payout', 50) }}">
                    </div>
                    <button type="submit"
                        class="inline-flex justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Save Payment Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Email Settings -->
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Email Settings</h2>
            <form action="{{ route('admin.settings.email.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="mail_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                        <input type="text" name="mail_host" id="mail_host" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('mail.mailers.smtp.host') }}">
                    </div>
                    <div>
                        <label for="mail_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                        <input type="number" name="mail_port" id="mail_port" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('mail.mailers.smtp.port') }}">
                    </div>
                    <div>
                        <label for="mail_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                        <input type="text" name="mail_username" id="mail_username" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            value="{{ config('mail.mailers.smtp.username') }}">
                    </div>
                    <div>
                        <label for="mail_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                        <input type="password" name="mail_password" id="mail_password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Leave blank to keep current password">
                    </div>
                    <button type="submit"
                        class="inline-flex justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout> 