<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\WriterManagementController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Writer\WriterController;
use App\Http\Controllers\Writer\AssignmentController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ReviewController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Quote request route
Route::post('/quote/request', function () {
    // Process the quote request
    session()->flash('success', 'Your quote request has been received. We will contact you shortly!');
    return redirect()->back();
})->name('quote.request');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Role-based redirection for dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'writer') {
            return redirect()->route('writer.dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        
        return view('dashboard');
    })->name('dashboard');

    // User settings (common for all roles)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', 'settings/profile')->name('index');
        Volt::route('profile', 'settings.profile')->name('profile');
        Volt::route('password', 'settings.password')->name('password');
        Volt::route('appearance', 'settings.appearance')->name('appearance');
    });
});

// Admin routes
Route::middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::put('/settings/payment', [AdminController::class, 'updatePaymentSettings'])->name('settings.payment.update');
    Route::put('/settings/email', [AdminController::class, 'updateEmailSettings'])->name('settings.email.update');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/disputes', [AdminController::class, 'disputes'])->name('disputes');
    
    // Admin messages routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('index');
        Route::get('/order/{order}', [\App\Http\Controllers\Admin\MessageController::class, 'showOrderMessages'])->name('order');
        Route::post('/order/{order}/send', [\App\Http\Controllers\Admin\MessageController::class, 'sendMessage'])->name('send');
        Route::get('/users', [\App\Http\Controllers\Admin\MessageController::class, 'listUsers'])->name('users');
        Route::get('/user/{user}', [\App\Http\Controllers\Admin\MessageController::class, 'showUserMessages'])->name('user');
        Route::post('/user/{user}/send', [\App\Http\Controllers\Admin\MessageController::class, 'sendDirectMessage'])->name('send-direct');
        Route::post('/mark-read/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('mark-read');
    });
    
    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderManagementController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderManagementController::class, 'show'])->name('show');
        Route::post('/{order}/assign-writer', [OrderManagementController::class, 'assignWriter'])->name('assign-writer');
        Route::post('/{order}/change-status', [OrderManagementController::class, 'changeStatus'])->name('change-status');
        Route::post('/{order}/resolve-dispute', [OrderManagementController::class, 'resolveDispute'])->name('resolve-dispute');
    });
    
    // Writer management
    Route::prefix('writers')->name('writers.')->group(function () {
        Route::get('/', [WriterManagementController::class, 'index'])->name('index');
        Route::get('/create', [WriterManagementController::class, 'create'])->name('create');
        Route::post('/', [WriterManagementController::class, 'store'])->name('store');
        Route::get('/{writer_user}', [WriterManagementController::class, 'show'])->name('show');
        Route::get('/{writer_user}/edit', [WriterManagementController::class, 'edit'])->name('edit');
        Route::put('/{writer_user}', [WriterManagementController::class, 'update'])->name('update');
        Route::patch('/{writer_user}/toggle-status', [WriterManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{writer_user}', [WriterManagementController::class, 'destroy'])->name('destroy');
    });
    
    // Writer applications (moved outside writers prefix)
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [WriterManagementController::class, 'applications'])->name('index');
        Route::get('/{application}', [WriterManagementController::class, 'viewApplication'])->name('view');
        Route::post('/{application}/approve', [WriterManagementController::class, 'approveApplication'])->name('approve');
        Route::post('/{application}/reject', [WriterManagementController::class, 'rejectApplication'])->name('reject');
    });
    
    // Payment management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/client', [PaymentManagementController::class, 'clientPayments'])->name('client');
        Route::get('/writer', [PaymentManagementController::class, 'writerPayments'])->name('writer');
        Route::get('/client/{payment}', [PaymentManagementController::class, 'showClientPayment'])->name('client.show');
        Route::get('/writer/{payment}', [PaymentManagementController::class, 'showWriterPayment'])->name('writer.show');
        Route::post('/writer/{payment}/process', [PaymentManagementController::class, 'processWriterPayment'])->name('writer.process');
        Route::post('/client/{payment}/refund', [PaymentManagementController::class, 'refundClientPayment'])->name('client.refund');
        Route::post('/writer/create/{order}', [PaymentManagementController::class, 'createWriterPayment'])->name('writer.create');
    });
});

// Writer routes
Route::middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\WriterMiddleware::class])
    ->prefix('writer')
    ->name('writer.')
    ->group(function () {
    Route::get('/dashboard', [WriterController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [WriterController::class, 'profile'])->name('profile');
    Route::put('/profile', [WriterController::class, 'updateProfile'])->name('profile.update');
    Route::post('/toggle-availability', [WriterController::class, 'toggleAvailability'])->name('toggleAvailability');
    Route::get('/orders', [WriterController::class, 'orders'])->name('orders');
    Route::get('/available-orders', [WriterController::class, 'availableOrders'])->name('available-orders');
    Route::get('/earnings', [WriterController::class, 'earnings'])->name('earnings');
    
    // Order specific routes for writers
    Route::get('/orders/available', [WriterController::class, 'availableOrders'])->name('orders.available');
    Route::get('/orders/{order}', [WriterController::class, 'viewOrder'])->name('orders.view');
    Route::post('/orders/{order}/claim', [WriterController::class, 'claimOrder'])->name('orders.claim');
    
    // Assignment management
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/{order}', [AssignmentController::class, 'show'])->name('show');
        Route::post('/{order}/accept', [AssignmentController::class, 'acceptOrder'])->name('accept');
        Route::post('/{order}/submit', [AssignmentController::class, 'submitWork'])->name('submit');
        Route::patch('/{order}/update-progress', [AssignmentController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{order}/start-revision', [AssignmentController::class, 'startRevision'])->name('start-revision');
        Route::post('/{order}/message', [AssignmentController::class, 'sendMessage'])->name('message');
        Route::post('/{order}/claim', [AssignmentController::class, 'claimOrder'])->name('claim');
        Route::get('/{order}/download-submission', [AssignmentController::class, 'downloadSubmission'])->name('download-submission');
    });
    
    // Revision management
    Route::get('/revisions', [AssignmentController::class, 'revisions'])->name('revisions');
    Route::get('/revisions/{order}', [AssignmentController::class, 'viewRevision'])->name('revisions.view');
    
    // Writer messages
    Route::post('/messages/{assignment}', [AssignmentController::class, 'sendMessage'])->name('messages.store');
    
    // Password update
    Route::post('/password/update', [WriterController::class, 'updatePassword'])->name('password.update');
});

// Client routes
Route::middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\ClientMiddleware::class])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::get('/payments', [ClientController::class, 'payments'])->name('payments');
    Route::get('/notifications', [ClientController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [ClientController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/mark-read', [ClientController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
    
    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/payment', [OrderController::class, 'payment'])->name('payment');
        Route::post('/{order}/payment', [OrderController::class, 'processPayment'])->name('process-payment');
        Route::post('/{order}/revision', [OrderController::class, 'requestRevision'])->name('request-revision');
        Route::post('/{order}/message', [OrderController::class, 'sendMessage'])->name('message');
        Route::post('/{order}/dispute', [OrderController::class, 'disputeOrder'])->name('dispute');
        Route::get('/{order}/download', [OrderController::class, 'download'])->name('download');
    });
    
    // Messages
    Route::post('/messages/{order}', [OrderController::class, 'sendMessage'])->name('messages.store');
    
    // Payment routes
    Route::get('/payments/{order}/pay', [ClientController::class, 'paymentForm'])->name('payments.pay');
    
    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/order/{order}', [ReviewController::class, 'create'])->name('create');
        Route::post('/order/{order}', [ReviewController::class, 'store'])->name('store');
        Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
    });
});

// File and Message Attachment routes
Route::middleware(['auth'])->group(function () {
    Route::get('/files/{file}/download', [App\Http\Controllers\FileController::class, 'download'])->name('files.download');
    Route::get('/messages/{message}/attachment', [App\Http\Controllers\MessageController::class, 'downloadAttachment'])->name('messages.attachment');
});

// Authentication routes
require __DIR__.'/auth.php';

// Test route
Route::get('/test', [App\Http\Controllers\TestController::class, 'test']);

// Test route with writer middleware 
Route::middleware(\App\Http\Middleware\WriterMiddleware::class)
    ->get('/test-writer', [App\Http\Controllers\TestController::class, 'test']);
