{{ Form::model($bank_transfer_payment, ['route' => ['bank-transfer-request.update', $bank_transfer_payment->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered ">
                <tr role="row">
                    <th>{{ __('Order Id') }}</th>
                    <td>{{ $bank_transfer_payment->order_id }}</td>
                </tr>
                <tr>
                    <th>{{__('status')}}</th>
                    <td>
                        @if($bank_transfer_payment->status == 'Approved')
                            <span class="bg-success p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                        @elseif($bank_transfer_payment->status == 'Pending')
                            <span class="bg-warning p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                        @else
                            <span class="bg-danger p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Appplied On') }}</th>
                    <td>{{ company_datetime_formate($bank_transfer_payment->created_at)}}</td>
                </tr>
                <tr>
                    <th>{{__('Name')}}</th>
                    <td>{{ !empty($bank_transfer_payment->User) ? $bank_transfer_payment->User->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{__('Price')}}</th>
                    <td>{{$bank_transfer_payment->price.' '.$bank_transfer_payment->price_currency}}</td>
                </tr>
                <tr>
                    <th>{{__('Request')}}</th>
                    @php
                        $requests = json_decode($bank_transfer_payment->request);
                        $modules = explode(',',$requests->user_module_input);
                    @endphp

                    <td>
                            <p><span class="text-primary">{{ __('Workspace: ')}}</span>{{ $requests->workspace_counter_input }}</p>
                            <p><span class="text-primary">{{ __('Users: ')}}</span>{{ $requests->user_counter_input }}</p>
                            <p><span class="text-primary">{{ __('Time Period: ')}}</span>{{ $requests->time_period }}</p>
                            <div class="">
                                <span class="text-primary">{{ __('Add-on: ')}}</span>
                                @foreach ($modules as $module)
                                    @if($module)
                                        <a href="{{ route('software.details',Module_Alias_Name($module)) }}" target="_new" class="btn btn-sm btn-warning me-2">{{ Module_Alias_Name($module)}}</a>
                                    @endif
                                @endforeach
                            </div>
                    </td>
                </tr>
                <tr>
                    <th>{{__('Attachment')}}</th>
                    <td>
                        @if (!empty($bank_transfer_payment->attachment) && (check_file($bank_transfer_payment->attachment)))
                            <div class="action-btn bg-primary ms-2">
                                <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" download>
                                    <i class="ti ti-download text-white"></i>
                                </a>
                            </div>
                            <div class="action-btn bg-secondary ms-2">
                                <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" target="_blank"  >
                                    <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                </a>
                            </div>
                        @else
                            {{ __('Not Found')}}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @if ($bank_transfer_payment->status == 'Pending')
        <div class="modal-footer">
            <a href=""></a>
            <input type="submit" value="{{ 'Reject' }}" class="btn btn-danger rounded" name="status">
            <input type="submit" value="{{ 'Approved' }}" class="btn btn-success rounded" name="status">
        </div>
    @endif
{{ Form::close() }}
