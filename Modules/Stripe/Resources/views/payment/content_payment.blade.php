<div class="payment-method">
    <div class="payment-title d-flex align-items-center justify-content-between">
        <h2 class="h5">{{ __('Stripe') }}</h2>
        <div class="payment-image">
            <img src="{{ asset('Modules/LMS/Resources/assets/imgs/visa.png') }}" alt="">
            <img src="{{ asset('Modules/LMS/Resources/assets/imgs/mastercard.png') }}" alt="">
            {{-- <img src="{{ asset('Modules/LMS/Resources/assets/imgs/skrill.png') }}" alt=""> --}}
        </div>
    </div>
    <p>{{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Maestro and Skrill.') }}</p>
    <form action="{{ route('content.pay.with.stripe', $store->slug) }}" role="form" method="post"
        class="payment-method-form" id="payment-form">
        @csrf
        <div class="pay-btn text-right">
            <button class="btn" type="submit">{{ __('Pay Now') }}</button>
        </div>
    </form>
</div>
