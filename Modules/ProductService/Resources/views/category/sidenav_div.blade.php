@permission('category manage')
    <div id="category-settings" class="">
        <div class="">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5 class="">
                                    {{ __('Category') }}
                                </h5>
                            </div>
                            <div class="col-1 text-end">
                                @permission('category create')
                                    <a  data-url="{{ route('category.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" title="{{__('Create')}}" data-title="{{__('Create New Category')}}"  class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                @endpermission
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table mb-0 pc-dt-simple" id="category">
                            <thead>
                            <tr>
                                <th> {{__('Category')}}</th>
                                <th> {{__('Type')}}</th>
                                @if (Laratrust::hasPermission('category edit') || Laratrust::hasPermission('category delete'))
                                 <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="font-style">{{ $category->name }}</td>
                                    <td class="font-style">
                                        {{ __(\Modules\ProductService\Entities\Category::$categoryType[$category->type]) }}
                                    </td>
                                @if (Laratrust::hasPermission('category edit') || Laratrust::hasPermission('category delete'))
                                    <td class="Action">
                                        <span>
                                                @permission('category edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm align-items-center" data-url="{{ route('category.edit',$category->id) }}" data-ajax-popup="true" data-title="{{__('Edit Product Category')}}" data-bs-toggle="tooltip" title="{{__('Create')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('category edit')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['category.destroy', $category->id],'id'=>'delete-form-'.$category->id]) !!}
                                                        <a  class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$category->id}}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                        </span>
                                    </td>
                                @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpermission
@push('scripts')
<script>
  var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
@endpush
