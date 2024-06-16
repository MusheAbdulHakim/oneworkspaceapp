<div class="position-absolute float-end" style="right: 50px; top: 17px;">
    @if (!empty($category))
    {{ Form::open(['route' => ['app-pin.category.destroy', $category->id], 'class' => 'm-0']) }}
    <button type="button" class="bs-pass-para show_confirm btn btn-sm btn-danger text-white"
        aria-label="Delete"
        data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('All Pinned Apps under this category will be removed. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $category->id }}">
        @method('DELETE')
        <i class="ti ti-trash"></i>
    </button>
    {{ Form::close() }}
    @endif
</div>

{{ Form::open(['route' => 'pin-apps.store','enctype'=>'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        @if (!empty($module) && $module != 'new')
        <input type="hidden" name="module" value="{{ $module }}">
        @else
        <div class="form-group col-md-12">
            {{ Form::label('module', __('Select Module'),['class'=>'form-label']) }}
            <select name="module" class="form-control">
                @php
                    $exceptions = ['ProductService','Stripe','Paypal'];
                @endphp
                @foreach (ActivatedModule() as $item)
                    @if (!in_array($item, $exceptions))
                    <option value="{{ $item }}">{{ $item }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        @endif
        <div class="form-group col-md-12">
            {{ Form::label('category', __('Category Name'), ['class' => 'form-label']) }}
            {{ Form::text('category', $category->name ?? '' , ['class' => 'form-control', 'required' => 'required']) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Pin') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
