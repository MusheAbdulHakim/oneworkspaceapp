@extends('layouts.main')
@section('page-title')
    {{__('Manage Purchase')}}
@endsection
@section('page-breadcrumb')
    {{__('Purchase')}}
@endsection
@push('scripts')
    <script>
        $('.copy_link').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('Modules/Pos/Resources/assets/css/custom.css') }}">
@endpush

@section('page-action')
    <div class="">
        @stack('addButtonHook')
        <a href="{{ route('purchase.grid') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @if(module_is_active('ProductService'))
        <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>
            @permission('purchase create')
                <a href="{{ route('purchase.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                    <i class="ti ti-plus"></i>
                </a>
            @endpermission
        @endif
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th> {{__('Purchase')}}</th>
                                <th> {{__('Vendor')}}</th>
                                <th> {{__('Category')}}</th>
                                <th> {{__('Purchase Date')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Laratrust::hasPermission('purchase edit') || Laratrust::hasPermission('purchase delete') || Laratrust::hasPermission('purchase show'))
                                    <th > {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                                @foreach ($purchases as $purchase)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}" class="btn btn-outline-primary">{{ App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id) }}</a>

                                    </td>
                                    @if(!empty($purchase->vender_name))
                                            <td> {{ (!empty( $purchase->vender_name)?$purchase->vender_name:'') }} </td>
                                    @elseif(empty($purchase->vender_name))
                                        <td>{{($purchase->user->name)}}</td>
                                    @else
                                        @if(module_is_active('Account'))
                                            <td> {{ (!empty( $purchase->vender)?$purchase->vender->name:'') }} </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    @endif

                                    @if(module_is_active('ProductService'))
                                          <td>{{ !empty($purchase->category)?$purchase->category->name:'-'}}</td>
                                    @else
                                      <td>-</td>
                                    @endif

                                    <td>{{ company_date_formate($purchase->purchase_date) }}</td>

                                    <td>
                                        @if($purchase->status == 0)
                                            <span class="purchase_status badge bg-secondary status-btn p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 1)
                                            <span class="purchase_status badge bg-warning status-btn p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 2)
                                            <span class="purchase_status badge bg-danger status-btn p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 3)
                                            <span class="purchase_status badge bg-info status-btn p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 4)
                                            <span class="purchase_status badge bg-primary status-btn p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                        @endif
                                    </td>



                                    @if(Laratrust::hasPermission('purchase edit') || Laratrust::hasPermission('purchase delete') || Laratrust::hasPermission('purchase show'))
                                        <td class="Action">
                                            <span>
                                                @permission('purchase show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('purchase edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('purchase.edit',\Crypt::encrypt($purchase->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('purchase delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['purchase.destroy', $purchase->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$purchase->id]) !!}
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}" data-confirm-yes="document.getElementById('delete-form-{{$purchase->id}}').submit();">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            </span>
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

