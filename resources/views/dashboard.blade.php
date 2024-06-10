@extends('layouts.main')
@section('page-title')
{{ __('Dashboard')}}
@endsection
@section('content')
<div class="row">
    @foreach (ActivatedModule() as $module)
    <div class="col-lg-4 col-md-6 col-12">
        @if (Route::has($module.'index'))
        <a href="{{ url($module) }}">
        @else
        <a href="javascript:void(0)">
        @endif
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2 mt-2">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-layout-2"></i>
                            </div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6 class="ml-4">{{$module}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection
