<?php

namespace Modules\Account\Providers;

use App\Events\BankTransferPaymentStatus;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\AamarPay\Events\AamarPaymentStatus;
use Modules\Account\Listeners\InvoiceBalanceTransfer;
use Modules\AuthorizeNet\Events\AuthorizeNetStatus;
use Modules\Benefit\Events\BenefitPaymentStatus;
use Modules\Cashfree\Events\CashfreePaymentStatus;
use Modules\Coingate\Events\CoingatePaymentStatus;
use Modules\Fedapay\Events\FedapayPaymentStatus;
use Modules\Flutterwave\Events\FlutterwavePaymentStatus;
use Modules\Iyzipay\Events\IyzipayPaymentStatus;
use Modules\Khalti\Events\KhaltiPaymentStatus;
use Modules\Mercado\Events\MercadoPaymentStatus;
use Modules\Midtrans\Events\MidtransPaymentStatus;
use Modules\Mollie\Events\MolliePaymentStatus;
use Modules\Paddle\Events\PaddlePaymentStatus;
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

class EventServiceProvider extends Provider
{

    protected $listen = [
        StripePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaypalPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        FlutterwavePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaystackPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        RazorpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MolliePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PayfastPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        YooKassaPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytabPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        SSpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        ToyyibpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        SkrillPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        IyzipayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytrPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        AamarPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        BenefitPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        CashfreePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        CoingatePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MercadoPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytmPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaddlePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MidtransPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        XenditPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        TapPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        KhaltiPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PhonePePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        AuthorizeNetStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PayHerePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaiementProPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        FedapayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        BankTransferPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
    ];
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
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
