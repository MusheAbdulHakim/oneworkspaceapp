@extends('layouts.main')
@section('page-title')
    {{__('Email Templates')}}
@endsection
@section("page-breadcrumb")
    {{__('Email Templates')}}
@endsection
@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header card-body">
                <h5></h5>
                {{Form::model($emailTemplate, array('route' => array('email_template.update', $emailTemplate->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="form-group col-md-12">
                        {{Form::label('name',__('Name'),['class'=>'col-form-label text-dark'])}}
                        {{Form::text('name',null,array('class'=>'form-control font-style','disabled'=>'disabled'))}}
                    </div>
                    <div class="form-group col-md-12">
                        {{Form::label('from',__('From'),['class'=>'col-form-label text-dark'])}}
                        {{Form::text('from',null,array('class'=>'form-control font-style','required'=>'required'))}}
                    </div>
                    {{Form::hidden('lang',$currEmailTempLang->lang,array('class'=>''))}}
                    <div class="col-12 text-end">
                        <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-8 col-12">
        <div class="card">
            <div class="card-header card-body">
                <h5></h5>
                <div class="row text-xs">

                    <h6 class="font-weight-bold mb-4">{{__('Variables')}}</h6>
                    @php
                        $variables = json_decode($currEmailTempLang->variables);
                    @endphp
                    @if(!empty($variables) > 0)
                    @foreach  ($variables as $key => $var)
                    <div class="col-6 pb-1">
                        <p class="mb-1">{{__($key)}} : <span class="pull-right text-primary">{{ '{'.$var.'}' }}</span></p>
                    </div>
                    @endforeach
                    @endif



                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <h5></h5>
            <div class="row">
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 ">
                    <div class="card sticky-top language-sidebar" >
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @foreach($languages as $key => $lang)
                            <a class="list-group-item list-group-item-action  {{($currEmailTempLang->lang == $key)?'active':''}}" href="{{route('manage.email.language',[$emailTemplate->id,$key])}}">
                                {{Str::ucfirst($lang)}}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    </div>

                <div class="col-lg-9 col-md-9 col-sm-9  card">
                    {{Form::model($currEmailTempLang, array('route' => array('store.email.language',$currEmailTempLang->parent_id), 'method' => 'PUT')) }}
                    <div class="row">
                        <div class="form-group col-12">
                            {{Form::label('subject',__('Subject'),['class'=>'col-form-label text-dark'])}}
                            {{Form::text('subject',null,array('class'=>'form-control font-style','required'=>'required'))}}
                        </div>
                        <div class="form-group col-12">
                            {{Form::label('content',__('Email Message'),['class'=>'col-form-label text-dark'])}}
                            {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote','id'=>'content','required'=>'required'))}}
                        </div>

                        <div class="col-md-12 text-end mb-3">
                            {{Form::hidden('lang',null)}}
                            <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
    </div>
</div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush
