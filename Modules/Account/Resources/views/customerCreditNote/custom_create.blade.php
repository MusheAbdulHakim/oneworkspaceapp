{{ Form::open(array('route' => array('custom-credits.store'),'mothod'=>'post')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('invoice', __('Invoice'),['class'=>'form-label']) }}
                <select class="form-control select" required="required" id="invoice" name="invoice">
                    <option value="0">{{__('Select Invoice')}}</option>
                    @foreach($invoices as $key=>$invoice)
                        <option value="{{$key}}">{{\App\Models\Invoice::invoiceNumberFormat($invoice)}}</option>
                    @endforeach
                </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::number('amount', !empty($invoiceDue)?$invoiceDue->getDue():0, array('class' => 'form-control','required'=>'required','step'=>'0.01','min'=>'0')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{Form::date('date',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('',__('Status'),['class'=>'form-label']) }}
            {{Form::select('status',$statues,null,array('class'=>'form-control select'))}}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
