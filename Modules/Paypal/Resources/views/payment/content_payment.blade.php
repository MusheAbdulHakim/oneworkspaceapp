<div class="payment-method">
    <div class="payment-title d-flex align-items-center justify-content-between">
        <h2 class="h5">{{ __('PayPal') }}</h2>
        <div class="payment-image">
            <img src="{{ asset('Modules/LMS/Resources/assets/imgs/paypal.png') }}" alt="">
        </div>
    </div>
    <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PayPal to finish complete your purchase.') }}
    </p>
    <form method="POST" action="{{ route('content.pay.with.paypal', $store->slug) }}" class="payment-method-form">
        @csrf
        <input type="hidden" name="product_id">
        <div class="pay-btn text-right">
            <button class="btn" type="submit">{{ __('Pay Now') }}</button>
        </div>
    </form>
</div>
