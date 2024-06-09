{{-- @extends('marketplace.marketplace') --}}
@extends('landingpage::layouts.marketplace')
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
                            <h2>{!! $page['menubar_page_name'] !!}</h2> 
                            <p>{!! $page['menubar_page_short_description'] !!}
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
                {!! $page['menubar_page_contant'] !!} </div>
        </div>
    </section>
</div>
<!-- wrapper end -->
@endsection

