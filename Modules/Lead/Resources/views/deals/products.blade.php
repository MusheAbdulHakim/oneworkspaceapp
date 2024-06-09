{{ Form::model($deal, array('route' => array('deals.products.update', $deal->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('products', __('Products'),['class'=>'col-form-label']) }}
            {{ Form::select('products[]', $products,null, array('class' => 'form-control choices','id'=>'choices-multiple','multiple'=>'')) }}
            <p class="text-danger d-none" id="product_validation">{{__('Products filed is required.')}}</p>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary" id="submit">{{__('Save')}}</button>
</div>

{{ Form::close() }}

<script>
    $(function(){
        $("#submit").click(function() {
            var product =  $("#choices-multiple option:selected").length;
            if(product == 0){
            $('#product_validation').removeClass('d-none')
                return false;
            }else{
            $('#product_validation').addClass('d-none')
            }
        });
    });
</script>
