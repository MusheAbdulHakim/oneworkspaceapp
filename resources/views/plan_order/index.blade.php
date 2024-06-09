@extends('layouts.main')
@section('page-title')
    {{ __('Order') }}
@endsection
@section('page-breadcrumb')
    {{ __('Order') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable pc-dt-simple" id="test">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Id') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Payment Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Coupon') }}</th>
                                    <th class="text-center">{{ __('Invoice') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($orders as $order)
                                    @php
                                        $user = App\Models\User::find($order->user_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ company_datetime_formate($order->created_at) }}</td>
                                        <td>{{ $order->user_name }}</td>
                                        <td>{{ $order->plan_name }}</td>
                                        <td>{{ $order->price . ' ' . $order->price_currency }}</td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>
                                            @if ($order->payment_status == 'succeeded')
                                                <span
                                                    class="bg-success p-1 px-3 rounded text-white">{{ ucfirst($order->payment_status) }}</span>
                                            @else
                                                <span
                                                    class="bg-danger p-2 px-3 rounded text-white">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>

                                        <td>{{ !empty($order->total_coupon_used) ? (!empty($order->total_coupon_used->coupon_detail) ? $order->total_coupon_used->coupon_detail->code : '-') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                                <a href="{{ $order->receipt }}" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Invoice') }}" target="_blank"
                                                    class="">
                                                    <i class="ti ti-file-invoice text-primary"></i>
                                                </a>
                                            @elseif($order->payment_type == 'Bank Transfer')
                                                <a href="{{ !empty($order->receipt) ? (check_file($order->receipt) ? get_file($order->receipt) : '#!') : '#!' }}"
                                                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Invoice') }}"
                                                    target="_blank" class="">
                                                    <i class="ti ti-file-invoice text-primary"></i>
                                                </a>
                                            @elseif($order->receipt == 'free coupon')
                                                <p>{{ __('Used 100 % discount coupon code.') }}</p>
                                            @elseif($order->payment_type == 'Manually')
                                                <p>{{ __('Manually plan upgraded by super admin') }}</p>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if($order->price != '0')
                                        <td>
                                            @foreach ($userOrders as $userOrder)
                                                @if ($user->active_plan == $order->plan_id && $order->order_id == $userOrder->order_id && $order->is_refund == 0)
                                                    <div class="badge bg-warning rounded p-2 px-3 ms-2">
                                                        <a href="{{ route('order.refund', [$order->id, $order->user_id]) }}"
                                                            class="mx-3 align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Refund') }}"
                                                            data-original-title="{{ __('Refund') }}">
                                                            <span class ="text-white">{{ __('Refund') }}</span>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                        @endif
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
