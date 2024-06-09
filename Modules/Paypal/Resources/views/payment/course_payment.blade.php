<div class="payment-method">
    <div class="payment-title d-flex align-items-center justify-content-between">
        <h4>{{ __('PayPal') }}</h4>
        <div class="payment-image extra-size d-flex align-items-center">
            <img src="{{ asset('Modules/LMS/Resources/assets/imgs/paypal.png') }}" alt="paypal">
        </div>
    </div>
    <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PayPal to finish complete your purchase.') }}
    </p>
    <form method="POST" action="{{ route('course.pay.with.paypal', $store->slug) }}"
        class="payment-method-form">
        @csrf
        <input type="hidden" name="product_id">
        <div class="form-group text-right">
            <button type="submit" class="btn">{{ __('Pay Now') }}</button>
        </div>
    </form>
</div>
