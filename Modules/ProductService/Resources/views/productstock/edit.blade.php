{{ Form::model($productService, array('route' => array('productstock.update', $productService->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-md-6">
            {{ Form::label('Product', __('Product'),['class'=>'form-label']) }}<br>
            {{$productService->name}}

        </div>
        <div class="form-group col-md-6">
            {{ Form::label('Product', __('SKU'),['class'=>'form-label']) }}<br>
            {{$productService->sku}}

        </div>

        <div class="form-group col-md-12">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::number('quantity',"", array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
