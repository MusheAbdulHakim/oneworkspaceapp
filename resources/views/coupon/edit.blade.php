{{Form::model($coupon, array('route' => array('coupons.update', $coupon->id), 'method' => 'PUT')) }}
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
            @if($coupon->type == 'fixed')
                {{Form::label('included_module',__('Included Plan'),['class'=>'form-label'])}}
                {{ Form::select('included_module[]',$plans,explode(',',$coupon->included_module),['class'=>'form-control multi-select choices','id'=>'included_module','multiple']) }}
            @endif
        </div>
        <div class="form-group col-md-6 excluded_module">
            @if($coupon->type == 'fixed')
                {{Form::label('excluded_module',__('Excluded Plan'),['class'=>'form-label'])}}
                {{ Form::select('excluded_module[]',$plans,explode(',',$coupon->excluded_module),['class'=>'form-control multi-select choices','id'=>'excluded_module','multiple']) }}
            @endif
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
            {{Form::number('usage_limit_per_coupon',$coupon->limit,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('usage_limit_per_user',__('Usage limit per User'),['class'=>'form-label'])}}
            {{Form::number('usage_limit_per_user',$coupon->limit_per_user,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('expiry_date',__('Expiry Date'),['class'=>'form-label'])}}
            {{Form::date('expiry_date',null,array('class'=>'form-control','required'=>'required'))}}
        </div>

         <div class="form-group col-md-12">
            {{Form::label('code',__('Code'),['class'=>'form-label'])}}
            {{Form::text('code',null,array('class'=>'form-control','required'=>'required'))}}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
<script>
    $(document).ready(function(){
        $('select[name=type]').trigger('change');
    });
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
        
        var inoption = '';
        var exoption = '';
        Object.keys(plans).forEach((value,key)=>{
            var included_module = '{{ json_encode(explode(',',$coupon->included_module)) }}';
            var excluded_module = '{{ json_encode(explode(',',$coupon->excluded_module)) }}';
            inoption += '<option value=' + value + (included_module.includes(value) ? ' selected' : '') + '>'+plans[value]+'</option>';
            exoption += '<option value=' + value + (excluded_module.includes(value) ? ' selected' : '') + '>'+plans[value]+'</option>';
        });
        var includedHTML = `<label for="included_module" class="form-label">Included Plan</label>
                    <select class="form-control multi-select choices" name="included_module[]" id="included_module" multiple>`+inoption+`</select>`;
        
        $('.included_module').html(includedHTML)

        var excludedHTML = `<label for="excluded_module" class="form-label">Excluded Plan</label>
                    <select class="form-control multi-select choices" name="excluded_module[]" id="excluded_module" multiple>`+exoption+`</select>`;

        $('.excluded_module').html(excludedHTML);

        $('#excluded_module').trigger('change');
        $('#included_module').trigger('change');
    }
</script>