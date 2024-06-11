<?php

namespace Modules\PabblyConnect\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\AamarPay\Events\AamarPaymentStatus;
use Modules\Benefit\Events\BenefitPaymentStatus;
use Modules\Cashfree\Events\CashfreePaymentStatus;
use Modules\Coingate\Events\CoingatePaymentStatus;
use Modules\Flutterwave\Events\FlutterwavePaymentStatus;
use Modules\Holidayz\Events\CreateHotel;
use Modules\Holidayz\Events\UpdateHotel;
use Modules\Iyzipay\Events\IyzipayPaymentStatus;
use Modules\Mercado\Events\MercadoPaymentStatus;
use Modules\Mollie\Events\MolliePaymentStatus;
use Modules\PabblyConnect\Listeners\CompanyPaymentLis;
use Modules\PabblyConnect\Listeners\CreateHotelLis;
use Modules\PabblyConnect\Listeners\WasteCollectionRequestAcceptLis;
use Modules\PabblyConnect\Listeners\WasteCollectionRequestRejectLis;
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
use Modules\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Modules\WasteManagement\Events\WasteCollectionRequestAccept;
use Modules\WasteManagement\Events\WasteCollectionRequestReject;
use Modules\YooKassa\Events\YooKassaPaymentStatus;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */

    protected $listen = [
        StripePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        MolliePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaystackPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        RazorpayPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        SkrillPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        IyzipayPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaypalPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaytabPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        CoingatePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaytmPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        MercadoPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        FlutterwavePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PayfastPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        ToyyibpayPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        YooKassaPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        SSpayPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaytrPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        AamarPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        CashfreePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        BenefitPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PhonePePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PayHerePaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        PaiementProPaymentStatus::class => [
            CompanyPaymentLis::class
        ],
        CreateHotel::class => [
            CreateHotelLis::class
        ],
        UpdateHotel::class => [
            CreateHotelLis::class
        ],
        WasteCollectionRequestAccept::class => [
            WasteCollectionRequestAcceptLis::class
        ],
        WasteCollectionRequestReject::class => [
            WasteCollectionRequestRejectLis::class
        ],
    ];

    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}