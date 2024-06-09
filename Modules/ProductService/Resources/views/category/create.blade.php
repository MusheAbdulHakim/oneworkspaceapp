{{ Form::open(['route' => 'category.store','enctype'=>'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-12 ">
            <input type="hidden" value="{{$types}}" name="type">
        </div>
        @if($types  != 0)
        <div class="form-group col-md-12">
            {{ Form::label('chart_account_id', __('Account'),['class'=>'form-label']) }}
            <select name="chart_account_id" class="form-control" required="required">
                @foreach ($chartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
                    @foreach ($subAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code'] }} - {{ $subAccount['name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        @endif
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'), ['class' => 'form-label']) }}
            {{ Form::color('color', '', ['class' => 'form-control jscolor', 'required' => 'required']) }}
            <small>{{ __('For chart representation') }}</small>
        </div>
        @stack('items_image')
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

@push('scripts')
<script>

    $(document).on('change', '#type', function () {
        var type = $(this).val();
        console.log(type)

        $.ajax({
            url: '{{route('category.getaccount')}}',
            type: 'POST',
            data: {
                "type": type,
                "_token": "{{ csrf_token() }}",
            },

            success: function (data) {
                $('#chart_account').empty();
                $.each(data.chart_accounts, function (key, value) {
                    $('#chart_account').append('<option value="' + key + '" class="subAccount">' + value + '</option>');
                    $.each(data.sub_accounts, function (subkey, subvalue) {
                        if(key == subvalue.account)
                        {
                            $('#chart_account').append('<option value="' + subvalue.id + '">' + '&nbsp; &nbsp;&nbsp;' + subvalue.name + '</option>');
                        }
                    });
                });
            }

        });
    });
</script>
@endpush
