<div class="modal-body">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 text-center">
                            <h6 >{{ __('Total Workspace') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-warning card-icon-text-space fs-5 mx-1"></i><span class="total_workspace fs-5">
                                    {{ $workspce_data['total_workspace'] }}</span>
                            </p>
                        </div>
                        <div class="col-4 text-center">
                            <h6 >{{ __('Active Workspace') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-primary card-icon-text-space fs-5 mx-1"></i><span class="active_workspace fs-5">{{ $workspce_data['active_workspace'] }}</span>
                            </p>
                        </div>
                        <div class="col-4 text-center">
                            <h6 >{{ __('Disable Workspace') }}</h6>
                            <p class=" text-sm mb-0">
                                <i
                                    class="ti ti-users text-danger card-icon-text-space fs-5 mx-1"></i><span class="disable_workspace fs-5">{{ $workspce_data['disable_workspace'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-xxl-12 col-md-12">
                <div class="p-3 card m-0">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        @foreach ($users_data as $key => $user_data)
                            @php
                                $workspace = \App\Models\WorkSpace::where('id', $user_data['workspace_id'])->first();
                            @endphp
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-capitalize {{ $loop->index == 0 ? 'active' : '' }}"
                                    id="pills-{{ strtolower($workspace->id) }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-{{ strtolower($workspace->id) }}"
                                    type="button">{{ $workspace->name }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-0 card-body">
                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($users_data as $key => $user_data)
                        @php
                            $users = \App\Models\User::where('created_by', $id)
                                ->where('workspace_id', $user_data['workspace_id'])
                                ->get();
                            $workspace = \App\Models\WorkSpace::where('id', $user_data['workspace_id'])->first();
                        @endphp
                            <div class="tab-pane text-capitalize fade show {{ $loop->index == 0 ? 'active' : '' }}"
                                id="pills-{{ strtolower($workspace->id) }}" role="tabpanel"
                                aria-labelledby="pills-{{ strtolower($workspace->id) }}-tab">

                                <div class="row">
                                    <div class="col-lg-11 col-md-10 col-sm-10 mt-3 text-end">
                                    <small class="text-danger my-3">{{__('* Please ensure that if you disable the workspace, all users within this workspace are also disabled.')}}</small>

                                    </div>
                                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                                        <div class="text-end">
                                            <div class="form-check form-switch custom-switch-v1 mt-3">
                                                <input type="checkbox" name="workspace_disable"
                                                    class="form-check-input input-primary is_disable" value="1"
                                                    data-id="{{ $user_data['workspace_id'] }}" data-company="{{ $id }}"
                                                    data-name="{{ __('workspace') }}"
                                                    {{ $workspace->is_disable == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="workspace_disable"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div class="row workspace"  data-workspace-id ={{ $workspace->id }}>
                                        <div class="col-4 text-center">
                                            <p class="text-sm mb-0" data-toggle="tooltip"
                                                data-bs-original-title="{{ __('Total Users') }}"><i
                                                    class="ti ti-users text-warning card-icon-text-space fs-5 mx-1"></i><span class="total_users fs-5">{{ $user_data['total_users'] }}</span>

                                            </p>
                                        </div>
                                        <div class="col-4 text-center">
                                            <p class="text-sm mb-0" data-toggle="tooltip"
                                                data-bs-original-title="{{ __('Active Users') }}"><i
                                                    class="ti ti-users text-primary card-icon-text-space fs-5 mx-1"></i><span class="active_users fs-5">{{ $user_data['active_users'] }}</span>
                                            </p>
                                        </div>
                                        <div class="col-4 text-center">
                                            <p class="text-sm mb-0" data-toggle="tooltip"
                                                data-bs-original-title="{{ __('Disable Users') }}"><i
                                                    class="ti ti-users text-danger card-icon-text-space fs-5 mx-1"></i><span class="disable_users fs-5">{{ $user_data['disable_users'] }}</span>
                                            </p>
                                        </div>
                                </div>
                                <div class="row my-2 " id="user_section_{{$workspace->id}}">
                                    @foreach ($users as $user)
                                        <div class="col-md-6 my-2">
                                            <div
                                                class="d-flex align-items-center justify-content-between list_colume_notifi pb-2">
                                                <div class="mb-3 mb-sm-0">
                                                    <h6>
                                                        <img src="{{ check_file($user->avatar) ? get_file($user->avatar) : get_file('uploads/users-avatar/avatar.png') }}"
                                                            class=" wid-30 rounded-circle mx-2" alt="image"
                                                            height="30">
                                                        <label for="user"
                                                            class="form-label">{{ $user->name }}</label>
                                                    </h6>
                                                </div>
                                                <div class="text-end">
                                                    <div class="form-check form-switch custom-switch-v1 mb-2">
                                                        <input type="checkbox" name="user_disable"
                                                            class="form-check-input input-primary is_disable"
                                                            value="1" data-id='{{ $user->id }}' data-company="{{ $id }}"
                                                            data-name="{{ __('user') }}"
                                                            {{ $user->is_disable == 1 ? 'checked' : '' }} {{ $workspace->is_disable == 1 ? '' : 'disabled' }}>
                                                        <label class="form-check-label" for="user_disable"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if (module_is_active('LMS'))
                                    <div class="row my-2 " data-workspace-id ={{ $workspace->id }}>
                                        @php
                                            $stores = \Modules\LMS\Entities\Store::where('workspace_id', $workspace->id)->first();
                                        @endphp
                                        <div class="col-md-6 my-2">
                                            <div class="mb-3 mb-sm-0 list_colume_notifi">
                                                <h4>{{ __('Store Details :')}}</h4>
                                                <span><label for="">{{ isset($stores) ? $stores->name : '' }}</label></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".is_disable", function() {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var company_id = $(this).attr('data-company');
        var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

        $.ajax({
            url: '{{ route('user.unable') }}',
            type: 'POST',
            data: {
                "is_disable": is_disable,
                "id": id,
                "name": name,
                "company_id": company_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if(data.success)
                {
                    if (name == 'workspace')
                    {
                        var container = document.getElementById('user_section_'+id);
                        var checkboxes = container.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(function(checkbox) {
                            if(is_disable == 0){
                                checkbox.disabled = true;
                                checkbox.checked = false;
                            }else{
                                checkbox.disabled = false;
                            }
                        });

                    }
                    $('.active_workspace').text(data.workspce_data.active_workspace);
                    $('.disable_workspace').text(data.workspce_data.disable_workspace);
                    $('.total_workspace').text(data.workspce_data.total_workspace);
                    $.each(data.users_data, function(workspaceName, userData)
                    {
                        var $workspaceElements = $('.workspace[data-workspace-id="' + userData.workspace_id + '"]');
                        // Update total_users, active_users, and disable_users for each workspace
                        $workspaceElements.find('.total_users').text(userData.total_users);
                        $workspaceElements.find('.active_users').text(userData.active_users);
                        $workspaceElements.find('.disable_users').text(userData.disable_users);
                    });

                    toastrs('success', data.success, 'success');
                }else{
                    toastrs('error', data.error, 'error');

                }
            }
        });
    });
</script>
