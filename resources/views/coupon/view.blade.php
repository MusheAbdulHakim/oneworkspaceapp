@extends('layouts.main')
@section('page-title')
    {{__('Manage Coupon Details')}}
@endsection
@section('breadcrumb')
    {{__('Coupon Details')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table  mb-0 pc-dt-simple" id="user-coupon">
                            <thead>
                            <tr>
                                <th> {{__('User')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Plan Name')}}</th>
                                <th>{{__('Payment Type')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($userCoupons as $userCoupon)
                                <tr class="font-style">
                                    <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
                                    @php
                                        $order = \App\Models\Order::where('order_id',$userCoupon->order)->first();
                                    @endphp
                                    <td>{{ !empty($order->plan_name) ? $order->plan_name : 'Basic Package' }}</td>
                                    <td>{{!empty($order->payment_type) ? $order->payment_type : '--'}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
