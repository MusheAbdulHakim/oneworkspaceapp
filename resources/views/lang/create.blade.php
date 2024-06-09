{{ Form::open(array('route' => array('store.language'))) }}
<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('code',__('Language Code'),['class'=>'form-label']) }}
                {{Form::text('code',null,array('class'=>'form-control','placeholder'=>__('Language Code'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('fullname',__('Full Name'),['class'=>'form-label']) }}
                {{Form::text('fullname',null,array('class'=>'form-control','placeholder'=>__('Enter Language Full Name'),'required'=>'required'))}}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
