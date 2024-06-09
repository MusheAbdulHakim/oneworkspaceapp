{{ Form::model($deal, array('route' => array('deals.users.update', $deal->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('users', __('User'),['class'=>'col-form-label']) }}
            {{ Form::select('users[]', $users,null, array('class' => 'form-control choices','id'=>'choices-multiple','multiple'=>'')) }}
            <p class="text-danger d-none" id="user_validation">{{__('Users filed is required.')}}</p>
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
            var user =  $("#choices-multiple option:selected").length;
            if(user == 0){
            $('#user_validation').removeClass('d-none')
                return false;
            }else{
            $('#user_validation').addClass('d-none')
            }
        });
    });
</script>
