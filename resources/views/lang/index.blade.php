@extends('layouts.main')

@section('page-title')
    {{ __('Manage Languages') }}
@endsection
@section('page-breadcrumb')
{{ __('Languages') }}
@endsection
@section('page-action')
<div class="float-end">
    @if ($lang != 'en')
        <div class="action-btn pb-0">
            <div class="form-check form-switch custom-switch-v1 mt-0">
                <input type="hidden" name="disable_lang" value="off">
                <input type="checkbox" class="form-check-input input-primary" name="disable_lang" data-bs-placement="top" title="{{ __('Enable/Disable') }}" id="disable_lang" data-bs-toggle="tooltip" {{ $langs->status == 1 ? 'checked':'' }} >
                <label class="form-check-label" for="disable_lang"></label>
            </div>
        </div>
    @endif

    @if ($lang != (\Auth::user()->lang ?? 'en'))
        <div class="action-btn ">
            {{ Form::open(['route' => ['lang.destroy', $lang], 'class' => 'm-0']) }}
            @method('DELETE')
            <a href="#"
            class=" mx-3 btn btn-sm  bg-danger align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete"
            data-confirm-yes="delete-form-{{ $lang }}"><i
                class="ti ti-trash text-white"></i></a>
            {{ Form::close() }}
        </div>
    @endif
</div>
@endsection

@section('content')
@php
    $modules = getshowModuleList();
    $module = Module_Alias_Name($module);
@endphp
<div class="row">
        <div class="card align-middle p-3">
            <ul class="nav nav-pills pb-3" id="pills-tab" role="tablist">
                <li class="nav-item px-1">
                    <a class="nav-link text-capitalize  {{ ( $module == 'general') ? ' active' : '' }} " href="{{ route('lang.index',[$lang]) }}">{{ __('General') }}</a>
                </li>
                @foreach ($modules as $item)
                    @php
                        $item=Module_Alias_Name($item);
                    @endphp
                    <li class="nav-item px-1">
                        <a class="nav-link text-capitalize  {{ ( $module == ($item)) ? ' active' : '' }} " href="{{ route('lang.index',[$lang,$item]) }}">{{$item}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($languages as $key => $language)
                            <a href="{{ route('lang.index', [$key,$module]) }}"
                                class="nav-link my-1 font-weight-bold @if ($key ==$lang) active @endif">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">{{ Str::ucfirst($language)}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            @if($module == 'general' || $module == '')
                <div class="card px-3">
                    <ul class="nav nav-pills nav-fill my-4 lang-tab">
                        <li class="nav-item">
                            <a data-href="#labels" class="nav-link active">{{ __('Labels') }}</a>
                        </li>

                        <li class="nav-item">
                            <a data-toggle="tab" data-href="#messages" class="nav-link">{{ __('Messages') }} </a>
                        </li>
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ route('lang.store.data', [$lang,$module]) }}">
                        @csrf
                        <div class="tab-content">
                            <div class="tab-pane active" id="labels">
                                <div class="row">
                                    @foreach ($arrLabel as $label => $value)
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label text-dark">{{ $label }}</label>
                                                <input type="text" class="form-control"
                                                    name="label[{{ $label }}]" value="{{ $value }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($module == 'general' || $module == '')
                                <div class="tab-pane" id="messages">
                                    @foreach ($arrMessage as $fileName => $fileValue)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h6>{{ ucfirst($fileName) }}</h6>
                                            </div>
                                            @foreach ($fileValue as $label => $value)
                                                @if (is_array($value))
                                                    @foreach ($value as $label2 => $value2)
                                                        @if (is_array($value2))
                                                            @foreach ($value2 as $label3 => $value3)
                                                                @if (is_array($value3))
                                                                    @foreach ($value3 as $label4 => $value4)
                                                                        @if (is_array($value4))
                                                                            @foreach ($value4 as $label5 => $value5)
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-3">
                                                                                        <label
                                                                                            class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}.{{ $label5 }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}][{{ $label5 }}]"
                                                                                            value="{{ $value5 }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label
                                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}]"
                                                                                        value="{{ $value4 }}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}</label>
                                                                            <input type="text" class="form-control"
                                                                                name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}]"
                                                                                value="{{ $value3 }}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6">
                                                                <div class="form-group mb-3">
                                                                    <label
                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}]"
                                                                        value="{{ $value2 }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label
                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}</label>
                                                            <input type="text" class="form-control"
                                                                name="message[{{ $fileName }}][{{ $label }}]"
                                                                value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary btn-block btn-submit ">
                        </div>
                    </form>
                </div>
            </div> <!-- end card-->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.lang-tab .nav-link', function() {
            $('.lang-tab .nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            $(this).addClass('active');
            var id = $('.lang-tab .nav-link.active').attr('data-href');
            $(id).addClass('active');
        });

        $(document).on('change','#disable_lang',function(){
            var val = $(this).prop("checked");
            if(val == true){
                    var langMode = 1;
            }
            else{
                var langMode = 0;
            }
            $.ajax({
                    type:'POST',
                    url: "{{route('disablelanguage')}}",
                    datType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "mode":langMode,
                        "lang":"{{ $lang }}"
                    },
                    success : function(data){
                        toastrs('Success',data.message, 'success')
                        setTimeout(function() {
                            location.reload(true);
                        }, 1500);
                    }
            });
        });
    </script>
@endpush
