{{ Form::model($category, ['route' => ['category.update', $category->id], 'method' => 'PUT','enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
        </div>

        @if($category->type  != 0)
        <div class="form-group col-md-12">
            {{ Form::label('chart_account_id', __('Account'),['class'=>'form-label']) }}
            <select name="chart_account_id" class="form-control" required="required">
                @foreach ($chartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount" {{ $key == $category->chart_account_id ? 'selected' : '' }}>{{ $chartAccount }}</option>
                    @foreach ($subAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5" {{ $subAccount['id'] == $category->chart_account_id ? 'selected' : '' }}> &nbsp; &nbsp;&nbsp; {{ $subAccount['code'] }} - {{ $subAccount['name'] }}</option>
                        @endif
                            @endforeach
                @endforeach
            </select>
        </div>

        @endif
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'), ['class' => 'form-label']) }}
            {{ Form::color('color', null, ['class' => 'form-control jscolor', 'required' => 'required']) }}
            <p class="small">{{ __('For chart representation') }}</p>
        </div>
        @stack('items_image_edit')
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
