@extends('layouts.main')
@push('scripts')
    <script>
        $(document).on('click', '.code', function () {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('page-title')
    {{__('Manage Coupon')}}
@endsection
@section('page-breadcrumb')
        {{__('Coupon')}}
@endsection

@section('page-action')
    <div>
    @permission('coupon create')
        <a href="#" data-size="lg" data-url="{{ route('coupons.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Coupon')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="coupon">
                            <thead>
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Code')}}</th>
                                <th> {{__('Type')}}</th>
                                <th> {{__('Discount')}}</th>
                                <th> {{__('Limit')}}</th>
                                <th> {{__('Limit Per User')}}</th>
                                <th> {{__('Used')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($coupons as $coupon)
                                <tr class="font-style">
                                    <td>{{ $coupon->name }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ \App\Models\Coupon::$couponType[$coupon->type] ?? null }}</td>
                                    <td>{{ $coupon->discount .( $coupon->type == 'percentage' ? ' (%)' : '')  }}</td>
                                    <td>{{ $coupon->limit }}</td>
                                    <td>{{ $coupon->limit_per_user }}</td>
                                    <td>{{ $coupon->used_coupon() }}</td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('coupons.show',$coupon->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('View')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            @permission('coupon edit')
                                                <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('coupons.edit',$coupon->id) }}" data-ajax-popup="true" data-title="{{__('Edit Coupon')}}" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('coupon delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{ Form::open(['route' => ['coupons.destroy', $coupon->id], 'class' => 'm-0']) }}
                                                    @method('DELETE')
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm  align-items-center  show_confirm"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Delete" aria-label="Delete"
                                                        data-confirm-yes="delete-form-{{ $coupon->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission
                                        </span>
                                    </td>
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
