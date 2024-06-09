{{Form::model($workSpace,array('route' => array('workspace.update', $workSpace->id), 'method' => 'PUT', 'id' => 'workspace-edit-form')) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control','required'=>'required','placeholder' => __('Enter Workspace Name')]) }}
    </div>
    <div class="form-group">
        {{ Form::label('slug', __('Slug'), ['class' => 'col-form-label']) }}
        {{ Form::text('slug', null, ['class' => 'form-control','required'=>'required','placeholder' => __('Enter Workspace Slug')]) }}
        <span id="slug-msg"></span>
    </div>

    {{-- custom domain code --}}
    <div class="col-md-6 mb-3">
        <label for="domain_switch">{{ __('Custom domain enable') }}</label>
        <div class="form-check form-switch custom-switch-v1 float-end">
            <input type="checkbox" name="domain_switch" class="form-check-input input-primary pointer" value="on"
                id="domain_switch" {{ $workSpace->enable_domain == 'on' ? 'checked' : ''}}>
            <label class="form-check-label" for="domain_switch"></label>
        </div>
    </div>
    <div class="row domain-setup d-none">
        <div class="col-md-6">
            <label class="btn btn-outline-primary w-100 {{ ( $workSpace->domain_type == null || $workSpace->domain_type == 'custom') ? 'active' : ''}}">
                <input type="radio" class="domain_click radio-button d-none" name="enable_domain" value="enable_domain"
                    id="enable_domain"  {{ ( $workSpace->domain_type == null || $workSpace->domain_type == 'custom') ? 'checked' : ''}} >
                {{ __('Domain') }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="btn btn-outline-primary w-100 {{ $workSpace->domain_type == 'subdomain' ? 'active' : ''}}">
                <input type="radio" class="domain_click radio-button d-none" name="enable_domain" value="enable_subdomain"
                    id="enable_subdomain" {{ $workSpace->domain_type == 'subdomain' ? 'checked' : ''}}>
                {{ __('Sub Domain') }}
            </label>
        </div>
    </div>


    <div class="row domain-setup mt-3">
        @if(!empty($custom_domain_request) && $custom_domain_request->status != '0')
        <div class="form-group col-md-12 domain">
            {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
            {{ Form::text('domains', $workSpace->domain, ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
            <small class="text-danger">{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP : '. $serverIp)}}</small>
        </div>
        @elseif(empty($custom_domain_request) && $workSpace->domain == null)
            <div class="form-group col-md-12 domain">
                {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
                {{ Form::text('domains', $workSpace->domain, ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
                <small class="text-danger">{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP : '. $serverIp)}}</small>
            </div>
        @endif
        <small class="text-danger domain {{  !empty($custom_domain_request) && $custom_domain_request->status == '0' ? '' : 'd-none' }}">{{ __('Note : You are already to requested for custom domain.')}}</small>

        <div class="form-group col-md-12 sundomain">
            {{ Form::label('store_subdomain', __('Sub Domain'), ['class' => 'form-label']) }}
            <div class="input-group">
                {{ Form::text('subdomain', $subdomain, ['class' => 'form-control', 'placeholder' => __('Enter Domain')]) }}
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">.{{ $subdomain_name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}

<script>
    $('#workspace-edit-form').submit(function (e) {
        e.preventDefault();
        var slug = $('#slug').val();
        $.ajax({
            url: '{{ route('workspace.check') }}',
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "workspace": "{{ $workSpace->id }}",
                "slug": slug,
            },
            beforeSend: function () {
                $(".loader-wrapper").removeClass('d-none');
            },
            success: function(data)
            {
                $('#slug-msg').empty();
                if(data.success)
                {
                    $('#workspace-edit-form').unbind('submit').submit();
                }
                else
                {
                    $('#slug-msg').addClass('text-danger').text(data.error);
                }
            }
        });
    });
</script>

{{-- custom domain code --}}
<script>
    $(document).ready(function () {
        setTimeout(() => {
            $('#domain_switch').trigger('change');
             $('input[name="enable_domain"]:checked').trigger('change');
        }, 300);
    });
    $(document).on('change', '#domain_switch', function() {
        if ($(this).is(':checked')) {
            $('.domain-setup').removeClass('d-none');
        } else {
            $('.domain-setup').addClass('d-none');
        }
        $('.sundomain').hide();
    });
    $(document).on('change', '.domain_click#enable_domain', function(e) {
        $('.domain').show();
        $('.sundomain').hide();
        $('#domainnote').show();
        $("#enable_domain").parent().addClass('active');
        $("#enable_storelink").parent().removeClass('active');
        $("#enable_subdomain").parent().removeClass('active');
    });
    $(document).on('change', '.domain_click#enable_subdomain', function(e) {
        $('.domain').hide();
        $('.sundomain').show();
        $('#domainnote').hide();
        $("#enable_subdomain").parent().addClass('active');
        $("#enable_domain").parent().removeClass('active');
        $("#enable_domain").parent().removeClass('active');
    });
</script>
