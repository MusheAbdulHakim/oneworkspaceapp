<?php

namespace App\Providers;

use App\Events\BankTransferRequestUpdate;
use App\Listeners\ReferralTransactionLis;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\AamarPay\Events\AamarPaymentStatus;
use Modules\AuthorizeNet\Events\AuthorizeNetStatus;
use Modules\Benefit\Events\BenefitPaymentStatus;
use Modules\Cashfree\Events\CashfreePaymentStatus;
use Modules\CinetPay\Events\CinetPayPaymentStatus;
use Modules\Coingate\Events\CoingatePaymentStatus;
use Modules\Fedapay\Events\FedapayPaymentStatus;
use Modules\Flutterwave\Events\FlutterwavePaymentStatus;
use Modules\Iyzipay\Events\IyzipayPaymentStatus;
use Modules\Khalti\Events\KhaltiPaymentStatus;
use Modules\Mercado\Events\MercadoPaymentStatus;
use Modules\Midtrans\Events\MidtransPaymentStatus;
use Modules\Mollie\Events\MolliePaymentStatus;
use Modules\Nepalste\Events\NepalstePaymentStatus;
use Modules\PaiementPro\Events\PaiementProPaymentStatus;
use Modules\Payfast\Events\PayfastPaymentStatus;
use Modules\PayHere\Events\PayHerePaymentStatus;
use Modules\Paypal\Events\PaypalPaymentStatus;
use Modules\Paystack\Events\PaystackPaymentStatus;
use Modules\PayTab\Events\PaytabPaymentStatus;
use Modules\Paytm\Events\PaytmPaymentStatus;
use Modules\PayTR\Events\PaytrPaymentStatus;
use Modules\PhonePe\Events\PhonePePaymentStatus;
use Modules\Razorpay\Events\RazorpayPaymentStatus;
use Modules\Skrill\Events\SkrillPaymentStatus;
use Modules\SSPay\Events\SSpayPaymentStatus;
use Modules\Stripe\Events\StripePaymentStatus;
use Modules\Tap\Events\TapPaymentStatus;
use Modules\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Modules\Xendit\Events\XenditPaymentStatus;
use Modules\YooKassa\Events\YooKassaPaymentStatus;

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
        BankTransferRequestUpdate::class => [
            ReferralTransactionLis::class,
        ],
        PaypalPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        StripePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        AamarPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        AuthorizeNetStatus::class => [
            ReferralTransactionLis::class,
        ],
        BenefitPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CashfreePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CinetPayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CoingatePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        FedapayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        FlutterwavePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        IyzipayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        KhaltiPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MercadoPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MidtransPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MolliePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaiementProPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PayfastPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PayHerePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaystackPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytabPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytmPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytrPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PhonePePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytabPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        RazorpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        SkrillPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        SSpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        TapPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        ToyyibpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        XenditPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        YooKassaPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        NepalstePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
