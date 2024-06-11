@extends('layouts.main')
@section('page-title')
    {{ __('Manage Menu Builder') }}
@endsection
@section('page-breadcrumb')
    {{ __('Menu Builder') }}
@endsection
@section('page-action')
    @permission('sidemenubuilder create')
        <div class="float-end">
            <a href="#" data-url="{{ route('sidemenubuilder.create') }}" data-size="lg" data-bs-toggle="tooltip"
                title="{{ __('Create') }}" data-ajax-popup="true" data-title="{{ __('Create New Menu Builder') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="custom_field">
                            <thead>
                                <tr>
                                    <th> {{ __('Menu name') }}</th>
                                    <th> {{ __('Menu type') }}</th>
                                    <th> {{ __('parent module') }}</th>
                                    <th> {{ __('Which Window') }}</th>
                                    <th> {{ __('Owner') }}</th>
                                    @if (Laratrust::hasPermission('sidemenubuilder edit') || Laratrust::hasPermission('sidemenubuilder delete'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menu_builder as $field)

                                    <tr>
                                        <td>{{ $field->name }}</td>
                                        @foreach ($menus as $key => $menu)
                                            @if ($field->menu_type == $key)
                                                <td>{{ $menu }}</td>
                                            @endif
                                        @endforeach
                                        <td>
                                            {{ !empty($field->getParentIdByName->name) ? $field->getParentIdByName->name : '-' }}
                                        </td>
                                        @foreach ($show_window as $key => $showwindow)
                                            @if ($field->window_type == $key)
                                                <td>{{ $showwindow }}</td>
                                            @endif
                                        @endforeach

                                        <td>
                                            {{ $field->creatorName->name }}
                                        </td>
                                        
                                        @if (Laratrust::hasPermission('sidemenubuilder edit') || Laratrust::hasPermission('sidemenubuilder delete'))
                                            <td class="Action">
                                                <span>
                                                    @permission('sidemenubuilder edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                                data-url="{{ route('sidemenubuilder.edit', $field->id) }}"
                                                                data-ajax-popup="true"
                                                                data-title="{{ __('Edit Menu Builder') }}" data-size="lg"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('sidemenubuilder delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['sidemenubuilder.destroy', $field->id],
                                                                'id' => 'delete-form-' . $field->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm align-items-center show_confirm"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                                data-original-title="{{ __('Delete') }}"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="document.getElementById('delete-form-{{ $field->id }}').submit();">
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
