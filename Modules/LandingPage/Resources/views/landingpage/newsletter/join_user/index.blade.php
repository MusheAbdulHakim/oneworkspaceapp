
<div class="border">
    <div class="p-3 border-bottom accordion-header">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-9 col-sm-9">
                <h5>{{ __('Info') }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($join_us) && (is_array($join_us) || is_object($join_us)))
                    @foreach ($join_us as $key => $value)
                    <tr>
                        <td>{{ $value->email }}</td>
                        <td>
                            <span>
                                <div class="action-btn bg-danger ms-2">
                                    {!! Form::open(['method' => 'GET', 'route' => ['join_us_destroy', $value->id],'id'=>'delete-form-'.$key]) !!}
                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{ 'delete-form-'.$key}}">
                                        <i class="ti ti-trash text-white"></i>
                                        </a>
                                    {!! Form::close() !!}
                                </div>
                            </span>
                        </td>
                    </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>