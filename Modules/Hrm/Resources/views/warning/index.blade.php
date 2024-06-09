@extends('layouts.main')
@section('page-title')
    {{ __('Manage Warning') }}
@endsection
@section('page-breadcrumb')
{{ __('Warning') }}
@endsection
@section('page-action')
<div>
    @permission('warning create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Warning') }}" data-url="{{route('warning.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Warning By') }}</th>
                                <th>{{ __('Warning To') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Warning Date') }}</th>
                                <th>{{ __('Description') }}</th>
                                @if (Laratrust::hasPermission('warning edit') || Laratrust::hasPermission('warning delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warnings as $warning)
                            <tr>
                                <td>{{ !empty( Modules\Hrm\Entities\Employee::getEmployee($warning->warning_by)) ? Modules\Hrm\Entities\Employee::getEmployee($warning->warning_by)->name : '--' }}</td>
                                <td>{{ !empty( Modules\Hrm\Entities\Employee::getEmployee($warning->warning_to)) ? Modules\Hrm\Entities\Employee::getEmployee($warning->warning_to)->name : '-- ' }}</td>
                                <td>{{ $warning->subject }}</td>
                                <td>{{ company_date_formate($warning->warning_date) }}</td>
                                <td>
                                    <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">{{  !empty($warning->description) ? $warning->description : '' }}
                                    </p>
                                </td>
                                @if (Laratrust::hasPermission('warning edit') || Laratrust::hasPermission('warning delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('warning edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('warning/' . $warning->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Warning') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('warning delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('warning.destroy', $warning->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$warning->id}}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                    {{Form::close()}}
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

