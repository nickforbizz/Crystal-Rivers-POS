<?php

namespace App\Providers;

use App\Events\CustomerCreated;
use App\Events\OrderCreated;
use App\Events\ProductCreated;
use App\Events\SupplierCreated;
use App\Events\TransactionCreated;
use App\Events\UserRegistered;
use App\Listeners\SendCustomerCreatedNotification;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\SendProductCreatedNotification;
use App\Listeners\SendSupplierCreatedNotification;
use App\Listeners\SendTransactionCreatedNotification;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ProductCreated::class => [
            SendProductCreatedNotification::class,
        ],

        SupplierCreated::class => [
            SendSupplierCreatedNotification::class,
        ],

        CustomerCreated::class => [
            SendCustomerCreatedNotification::class,
        ],

        OrderCreated::class => [
            SendOrderCreatedNotification::class,
        ],

        TransactionCreated::class => [
            SendTransactionCreatedNotification::class,
        ],

        UserRegistered::class =>  [SendWelcomeEmail::class, ]
    ];
    
    

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Event::listen(
        //     UserRegistered::class,
        //     [SendWelcomeEmail::class, 'handle']
        // );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
