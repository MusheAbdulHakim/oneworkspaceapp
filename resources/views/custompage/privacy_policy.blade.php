@extends($layout)
@section('page-title')
    {{ __('Add-on Listing') }}
@endsection
@section('content')
<!-- wrapper start -->
<div class="wrapper">
    <section class="common-banner-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="common-banner-content">
                        <div class="section-title text-center">
                            <h2>{!! 'Privacy Policy' !!}</h2>
                            <p>{!! 'Protecting your privacy is our priority at WorkDo Dash, ensuring your data is used transparently and securely.' !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="product-listing-section product-custom-page padding-bottom">
        <div class="container">
            <div class="listing-info padding-top ">
                {!! 'At WorkDo Dash, we prioritize your privacy and are committed to safeguarding your personal information. Our privacy policy outlines the types of data we collect, how we use it, and the measures we take to protect it. We collect information to enhance user experience, provide personalized services, and ensure the security of our platform. We do not share your personal data with third parties without your consent, except as required by law or to protect our rights. By using WorkDo Dash, you agree to the collection and use of information in accordance with this policy, ensuring transparency and trust in all our interactions.' !!} </div>
        </div>
    </section>
</div>
<!-- wrapper end -->
@endsection

