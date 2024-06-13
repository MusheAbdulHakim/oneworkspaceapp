@extends('layouts.main')
@section('page-title')
{{ __('Dashboard')}}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <x-active-modules />
    </div>
    <div class="col-12">

    </div>
    @php
        $activeModules = ActivatedModule();
    @endphp
    @if (!empty($activeModules))
        @if (in_array('Hrm',$activeModules))
        @if (Auth::user()->isAbleTo('hrm dashboard manage'))
            @include('hrm::components.calendar')
        @endif
        @endif
        @if (in_array('Account',$activeModules))
        @endif
    @endif
</div>
@endsection
