@permission('pabbly manage')
    <div class="card" id="pabbly-sidenav">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <h5 class="">{{ __('Pabbly Connect') }}</h5>
                    <small>{{ __('Edit your Pabbly Connect settings') }}</small>
                </div>
                @permission('pabbly create')
                    <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                            data-title="{{ __('Create New Pabbly Connection') }}" data-url="{{ route('pabbly.connect.create') }}"
                            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                @endpermission
            </div>
        </div>

        <div class="card-body" style="max-height: 270px; overflow:auto">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Add-On') }}</th>
                            <th>{{ __('Module') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Url') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($pabbly_module as $key => $module)
                            @if (!empty($module->module) && ( module_is_active($module->module->module) || $module->module->module == 'general'))
                                <tr>
                                    <td class="text-capitalize">{{ Module_Alias_Name($module->module->module)}}</td>
                                    <td>{{ $module->module->submodule }}</td>
                                    <td>{{ $module->method }}</td>
                                    <td>{{ $module->url }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @permission('pabbly edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('pabbly.connect.edit', $module->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-title="Edit Pabbly"
                                                        data-toggle="tooltip" data-bs-original-title="Edit">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('pabbly delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['pabbly.connect.delete', $module->id],
                                                        'id' => 'delete-form-' . $module->id,
                                                    ]) !!}
                                                    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                        data-original-title="{{ __('Delete') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="document.getElementById('delete-form-{{ $module->id }}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endpermission