{{ Form::open(array('url' => 'coupons','method' =>'post')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),['class'=>'form-label'])}}
            {{Form::text('name',null,array('class'=>'form-control font-style','required'=>'required'))}}
        </div>

        <div class="form-group col-md-12">
            {{Form::label('type',__('Type'),['class'=>'form-label'])}}
            {{Form::select('type',$coupanType,null,array('class'=>'form-control font-style','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6 included_module">
           
        </div>
        <div class="form-group col-md-6 excluded_module">
           
        </div>
        <div class="form-group col-md-6">
            {{Form::label('minimum_spend',__('Minimum Spend'),['class'=>'form-label'])}}
            {{ Form::number('minimum_spend',null,['class'=>'form-control','required'=>'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('maximum_spend',__('Maximum Spend'),['class'=>'form-label'])}}
            {{ Form::number('maximum_spend',null,['class'=>'form-control','required'=>'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('discount',__('Discount'),['class'=>'form-label'])}}
            {{Form::number('discount',null,array('class'=>'form-control','required'=>'required','step'=>'0.01'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('usage_limit_per_coupon',__('Usage limit per coupon'),['class'=>'form-label'])}}
            {{Form::number('usage_limit_per_coupon',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('usage_limit_per_user',__('Usage limit per User'),['class'=>'form-label'])}}
            {{Form::number('usage_limit_per_user',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('expiry_date',__('Expiry Date'),['class'=>'form-label'])}}
            {{Form::date('expiry_date',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('code',__('Code'),['class'=>'form-label'])}}
            <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-6">
                    <input type="radio" id="manual_code" value="manual" name="coupon_type" class="form-check-input code" checked="checked">
                    <label class="custom-control-label " for="manual_code">{{__('Manual')}}</label>
                </div>
                <div class="form-check form-check-inline form-group col-md-6">
                    <input type="radio" id="auto_code" value="auto" name="coupon_type" class="form-check-input code">
                    <label class="custom-control-label" for="auto_code">{{__('Auto Generate')}}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12 d-block" id="manual">
            <input class="form-control font-uppercase" name="manualCode" type="text">
        </div>
        <div class="form-group col-md-12 d-none" id="auto">
            <div class="row">
                <div class="col-md-10">
                    <input class="form-control" name="autoCode" type="text" id="auto-code">
                </div>
                <div class="col-md-2">
                    <a href="#" class="btn btn-primary" id="code-generate"><i class="ti ti-history"></i></a>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).on('change','#included_module', function(){
        
        var plansJsonString = '{!! $plans !!}';
        var plans = JSON.parse(plansJsonString); 
        var selectedPlans = $(this).val();
        var excludedSelected = $('#excluded_module').val();
        var filteredPlans = {};
        selectedPlans = selectedPlans ? Array.isArray(selectedPlans) ? selectedPlans : [selectedPlans] : [];

        Object.keys(plans).forEach(function(key) {
            if (!selectedPlans.includes(key)) {
                filteredPlans[key] = plans[key];
            }
        });
        var options = '';
        Object.keys(filteredPlans).forEach(function(value){
            options += '<option value=' + value + (excludedSelected.includes(value) ? ' selected' : '') + '>' + filteredPlans[value] + '</option>';

        });

        var select = `<label for="excluded_module" class="form-label">Excluded Plan</label>
                        <select class="form-control multi-select choices" name="excluded_module[]" id="excluded_module" multiple>`+options+`</select>`;
        $('.excluded_module').html(select);
        
        var multipleCancelButton = new Choices('#excluded_module', {
            removeItemButton: true,
        });
        
    });

    $(document).on('change','#excluded_module', function(){
        var plansJsonString = '{!! $plans !!}';
        var plans = JSON.parse(plansJsonString); 
        var selectedPlans = $(this).val();
        var includedSelected = $('#included_module').val();
        var filteredPlans = {};
        selectedPlans = selectedPlans ? Array.isArray(selectedPlans) ? selectedPlans : [selectedPlans] : [];

        Object.keys(plans).forEach(function(key) {
            if (!selectedPlans.includes(key)) {
                filteredPlans[key] = plans[key];
            }
        });

        var options = '';
        Object.keys(filteredPlans).forEach(function(value){
            options += '<option value=' + value + (includedSelected.includes(value) ? ' selected' : '') + '>' + filteredPlans[value] + '</option>';
        });

        var select = `<label for="included_module" class="form-label">Included Plan</label>
            <select class="form-control multi-select choices" name="included_module[]" id="included_module" multiple>`+options+`</select>`;
        $('.included_module').html(select);

        var multipleCancelButton = new Choices('#included_module', {
            removeItemButton: true,  
        });
    });

    $(document).on('change','select[name=type]',function(){
        var selectedVal = $(this).val();
        if(selectedVal == 'fixed'){
            renderHTML();
        }
        else{
            $('.included_module').html('');
            $('.excluded_module').html('');
        }
    });
    
    function renderHTML(){
        var plansJsonString = '{!! $plans !!}';
        var plans = JSON.parse(plansJsonString); 
        
        var option = '';
        Object.keys(plans).forEach((value,key)=>{
            option += '<option value='+value+'>'+plans[value]+'</option>';
        });
        var includedHTML = `<label for="included_module" class="form-label">Included Plan</label>
                    <select class="form-control multi-select choices" name="included_module[]" id="included_module" multiple>`+option+`</select>`;
        
        $('.included_module').html(includedHTML)

        var excludedHTML = `<label for="excluded_module" class="form-label">Excluded Plan</label>
                    <select class="form-control multi-select choices" name="excluded_module[]" id="excluded_module" multiple>`+option+`</select>`;

        $('.excluded_module').html(excludedHTML);

        var multipleCancelButton = new Choices('#included_module', {
            removeItemButton: true,  
        });
        var multipleCancelButton = new Choices('#excluded_module', {
            removeItemButton: true,  
        });
    }
</script>