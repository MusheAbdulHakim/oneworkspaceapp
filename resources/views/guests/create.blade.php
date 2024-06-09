{{ Form::open(['url' => 'coupons','method' =>'post']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,['class'=>'form-control font-style','required'=>'required'])}}
            </div>

            <div class="form-group col-md-12">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                {{Form::email('email',null,['class'=>'form-control font-style','required'=>'required'])}}
            </div>

            <div class="form-group col-md-12">
                {{Form::label('phone',__('Phone'),['class'=>'form-label'])}}
                {{Form::text('phone',null,['class'=>'form-control font-style','required'=>'required'])}}
            </div>

            <div class="form-group col-md-12">
                {{Form::label('city',__('City'),['class'=>'form-label'])}}
                {{Form::text('city',null,['class'=>'form-control font-style','required'=>'required'])}}
            </div>

            <div class="form-group col-md-12">
                {{Form::label('country',__('Country'),['class'=>'form-label'])}}
                {{Form::select('country',[] , null,['class'=>'form-control font-style','required'=>'required'])}}
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}
