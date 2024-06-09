@extends('layouts.main')

@section('page-title')
    {{ $deal->name }}
@endsection

@push('css')
    <style>
        .nav-tabs .nav-link-tabs.active {
            background: none;
        }

        .deal_status {
            float: right;
            position: absolute;
            right: 0;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('Modules/Lead/Resources/assets/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css') }}">
@endpush

@php
    $deal->activities = $deal->activities->load('user');
    $deal->discussions = $deal->discussions->load('user');
    $deal->calls = $deal->calls->load('getDealCallUser');
@endphp

@push('scripts')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>

    <script src="{{ asset('Modules/Lead/Resources/assets/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
    <script>
        $(document).on("change", "#change-deal-status select[name=deal_status]", function() {
            $('#change-deal-status').submit();
        });

        @if (Auth::user()->type != 'client' || in_array('Client View Files', $permission))
            Dropzone.autoDiscover = false;
            myDropzone2 = new Dropzone("#dropzonewidget2", {
                maxFiles: 20,
                maxFilesize: 20,
                parallelUploads: 1,
                acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
                url: "{{ route('deals.file.upload', $deal->id) }}",
                success: function(file, response) {
                    if (response.is_success) {
                        dropzoneBtn(file, response);
                    } else {
                        myDropzone2.removeFile(file);
                        toastrs('Error', response.error, 'error');
                    }
                },
                error: function(file, response) {
                    myDropzone2.removeFile(file);
                    if (response.error) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response, 'error');
                    }
                }
            });
            myDropzone2.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("deal_id", {{ $deal->id }});
            });

            function dropzoneBtn(file, response) {
                var download = document.createElement('a');
                download.setAttribute('href', response.download);
                download.setAttribute('class', "btn btn-sm btn-primary m-1");
                download.setAttribute('data-toggle', "tooltip");
                download.setAttribute('download', file.name);
                download.setAttribute('data-original-title', "{{ __('Download') }}");
                download.innerHTML = "<i class='ti ti-download'></i>";

                var del = document.createElement('a');
                del.setAttribute('href', response.delete);
                del.setAttribute('class', "btn btn-sm btn-danger mx-1");
                del.setAttribute('data-toggle', "tooltip");
                del.setAttribute('data-original-title', "{{ __('Delete') }}");
                del.innerHTML = "<i class='ti ti-trash'></i>";

                del.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (confirm("Are you sure ?")) {
                        var btn = $(this);
                        $.ajax({
                            url: btn.attr('href'),
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
                            success: function(response) {
                                if (response.is_success) {
                                    btn.closest('.dz-image-preview').remove();
                                } else {
                                    toastrs('Error', response.error, 'error');
                                }
                            },
                            error: function(response) {
                                response = response.responseJSON;
                                if (response.is_success) {
                                    toastrs('Error', response.error, 'error');
                                } else {
                                    toastrs('Error', response, 'error');
                                }
                            }
                        })
                    }
                });

                var html = document.createElement('div');
                html.appendChild(download);
                @if (Auth::user()->type != 'client')
                    @permission('deal edit')
                        html.appendChild(del);
                    @endpermission
                @endif

                file.previewTemplate.appendChild(html);
            }
            @foreach ($deal->files as $file)

                // Create the mock file:
                var mockFile2 = {
                    name: "{{ $file->file_name }}",
                    size: "{{ get_size(get_file($file->file_path)) }}"
                };
                // Call the default addedfile event handler
                myDropzone2.emit("addedfile", mockFile2);
                // And optionally show the thumbnail of the file:
                myDropzone2.emit("thumbnail", mockFile2, "{{ get_file($file->file_path) }}");
                myDropzone2.emit("complete", mockFile2);

                dropzoneBtn(mockFile2, {
                    download: "{{ get_file($file->file_path) }}",
                    delete: "{{ route('deals.file.delete', [$deal->id, $file->id]) }}"
                });
            @endforeach
        @endif


        @permission('deal task edit')
            $(document).on("click", ".task-checkbox", function() {
                var chbox = $(this);
                var lbl = chbox.parent().parent().find('label');

                $.ajax({
                    url: chbox.attr('data-url'),
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: chbox.val()
                    },
                    type: 'PUT',
                    success: function(response) {
                        if (response.is_success) {
                            chbox.val(response.status);
                            if (response.status) {
                                lbl.addClass('strike');
                                lbl.find('.badge').removeClass('bg-warning').addClass('bg-success');
                            } else {
                                lbl.removeClass('strike');
                                lbl.find('.badge').removeClass('bg-success').addClass('bg-warning');
                            }
                            lbl.find('.badge').html(response.status_label);

                            toastrs('Success', response.success, 'success');
                        } else {
                            toastrs('Error', response.error, 'error');
                        }
                    },
                    error: function(response) {
                        response = response.responseJSON;
                        if (response.is_success) {
                            toastrs('Error', response.error, 'error');
                        } else {
                            toastrs('Error', response, 'error');
                        }
                    }
                })
            });
        @endpermission

        $(document).ready(function() {
            var tab = 'general';
            @if ($tab = Session::get('status'))
                var tab = '{{ $tab }}';
            @endif
            $("#myTab2 .nav-link-tabs[href='#" + tab + "']").trigger("click");
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 170,
            });
        });
        $(document).ready(function() {
            $('.summernote').on('summernote.blur', function() {
                $.ajax({
                    url: "{{ route('deals.note.store', $deal->id) }}",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        notes: $(this).val()
                    },
                    type: 'POST',
                    success: function(response) {
                        if (response.is_success) {
                            // show_toastr('Success', response.success,'success');
                        } else {
                            toastrs('Error', response.error, 'error');
                        }
                    },
                    error: function(response) {
                        response = response.responseJSON;
                        if (response.is_success) {
                            toastrs('Error', response.error, 'error');
                        } else {
                            toastrs('Error', response, 'error');
                        }
                    }
                })
            });
        });

        if ($(".summernote").length > 0) {
            $('.summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 230,
            });
        }
    </script>
    {{-- Custom field description --}}
    <script>
        document.querySelectorAll('.description-container').forEach(function (container) {
            container.addEventListener('click', function () {
                var shortDescription = container.querySelector('.shortDescription');
                var fullDescription = container.querySelector('.fullDescription');

                if (shortDescription.style.display === 'block' || shortDescription.style.display === '') {
                    shortDescription.style.display = 'none';
                    fullDescription.style.display = 'block';
                } else {
                    shortDescription.style.display = 'block';
                    fullDescription.style.display = 'none';
                }
            });
        });
    </script>
@endpush

@section('page-breadcrumb')
    {{ __('Deals') }},
    {{ __($deal->name) }}
@endsection

@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('deal edit')
            <a class="btn btn-sm btn-primary btn-icon " data-size="md" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Labels') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Label') }}"
                data-url="{{ URL::to('deals/' . $deal->id . '/labels') }}"><i class="ti ti-tag text-white"></i></a>
            <a class="btn btn-sm btn-primary btn-icon " data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Edit') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Deal') }}"
                data-url="{{ URL::to('deals/' . $deal->id . '/edit') }}"><i class="ti ti-pencil text-white"></i></a>
        @endpermission
        @if ($deal->status == 'Won')
            <a href="#" class="btn btn-sm btn-success btn-icon ">{{ __($deal->status) }}</a>
        @elseif($deal->status == 'Loss')
            <a href="#" class="btn btn-sm btn-danger btn-icon">{{ __($deal->status) }}</a>
        @else
            <a href="#" class="btn btn-sm btn-info btn-icon">{{ __($deal->status) }}</a>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a class="list-group-item list-group-item-action border-0" href="#general">{{ __('General') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Tasks', $permission))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#tasks">{{ __('Tasks') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Products', $permission))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#products-users">{{ __('Users | Products') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Sources', $permission))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#sources-emails">{{ __('Sources | Emails') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif

                            <a class="list-group-item list-group-item-action border-0"
                                href="#discussion-notes">{{ __('Discussion | Notes') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Files', $permission))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#files">{{ __('Files') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif

                            @if (!Auth::user()->hasRole('super addmin'))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#clients">{{ __('Clients') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if (!Auth::user()->hasRole('super addmin'))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#calls">{{ __('Calls') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            @if (!Auth::user()->hasRole('super addmin'))
                                <a class="list-group-item list-group-item-action border-0"
                                    href="#activity">{{ __('Activity') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="general">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti ti-test-pipe"></i>
                                            </div>
                                            <div class="ms-2">
                                                <strong>{{ __('Pipeline') }}</strong>
                                                <h5 class="mb-0 text-success">{{ $deal->pipeline->name }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 my-3 my-sm-0">
                                        <div class="d-flex align-items-start">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-server"></i>
                                            </div>
                                            <div class="ms-2">
                                                <strong>{{ __('Stage') }}</strong>
                                                <h5 class="text-info">{{ $deal->stage->name }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-calendar"></i>
                                            </div>
                                            <div class="ms-2">
                                                <strong>{{ __('Created') }}</strong>
                                                <h5 class="text-warning">{{ company_date_formate($deal->created_at) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-report-money"></i>
                                            </div>
                                            <div class="ms-2">
                                                <strong>{{ __('Price') }}</strong>
                                                <h5 class="text-danger">{{ currency_format_with_sym($deal->price) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-6">
                                        @permission('deal edit')
                                            <span class="py-0">
                                                {{ Form::open(['route' => ['deals.change.status', $deal->id], 'id' => 'change-deal-status']) }}
                                                {{ Form::select('deal_status', Modules\Lead\Entities\Deal::$statues, $deal->status, ['class' => 'form-control select2 px-2', 'id' => 'deal_status', 'style' => 'width: 80px;']) }}
                                                {{ Form::close() }}
                                            </span>
                                        @endpermission
                                    </div>

                                    @if(!empty($customFields) && count($deal->customField) > 0)
                                        @foreach($customFields as $key => $field)
                                            <div class="col-md-3 col-sm-3 mt-4">
                                                <div class="d-flex align-items-start">
                                                    @if ($key % 4 === 0)
                                                        <div class="theme-avtar bg-success {{ $field->type == 'textarea' && !empty($deal->customField[$field->id]) ? 'mt-2' : '' }}">
                                                            <i class="ti ti-circle-plus"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{Str::ucfirst($field->name)}}</strong><br>
                                                            @if ($field->type == 'attachment')
                                                                <a href="{{ !empty($deal->customField[$field->id]) ? get_file($deal->customField[$field->id]) : get_file('Modules/Lead/Resources/assets/upload/default-img.png') }}"
                                                                    target=_blank
                                                                    class="btn btn-md p-0"
                                                                    data-bs-toggle="tooltip" title="{{ __('Preview') }}">
                                                                    <h5 class="text-success">{{ __('Preview Attachment') }}
                                                                        <i class="ti ti-arrow-up-right"></i>
                                                                    </h5>
                                                                </a>
                                                            @else
                                                                @if ($field->type == 'textarea')
                                                                    <div class="description-container" data-key="{{ $key }}">
                                                                        <h5 class="text-success shortDescription" id="shortDescription{{$key}}">
                                                                            {{ \Illuminate\Support\Str::limit(!empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-', 40) }}
                                                                        </h5>
                                                                        <h5 class="text-success fullDescription" style="display: none;">
                                                                            {{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}
                                                                        </h5>
                                                                    </div>
                                                                @else
                                                                    <h5 class="text-success">{{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}</h5>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @elseif($key % 4 === 1)
                                                        <div class="theme-avtar bg-info {{ $field->type == 'textarea' && !empty($deal->customField[$field->id]) ? 'mt-2' : '' }}">
                                                            <i class="ti ti-circle-plus"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{Str::ucfirst($field->name)}}</strong><br>
                                                            @if ($field->type == 'attachment')
                                                                <a href="{{ !empty($deal->customField[$field->id]) ? get_file($deal->customField[$field->id]) : get_file('Modules/Lead/Resources/assets/upload/default-img.png') }}"
                                                                    target=_blank
                                                                    class="btn btn-md p-0"
                                                                    data-bs-toggle="tooltip" title="{{ __('Preview') }}">
                                                                    <h5 class="text-info">{{ __('Preview Attachment') }}
                                                                        <i class="ti ti-arrow-up-right"></i>
                                                                    </h5>
                                                                </a>
                                                            @else
                                                                @if ($field->type == 'textarea')
                                                                    <div class="description-container" data-key="{{ $key }}">
                                                                        <h5 class="text-info shortDescription" id="shortDescription{{$key}}">
                                                                            {{ \Illuminate\Support\Str::limit(!empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-', 40) }}
                                                                        </h5>
                                                                        <h5 class="text-info fullDescription" style="display: none;">
                                                                            {{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}
                                                                        </h5>
                                                                    </div>
                                                                @else
                                                                    <h5 class="text-info">{{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}</h5>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @elseif($key % 4 === 2)
                                                        <div class="theme-avtar bg-warning {{ $field->type == 'textarea' && !empty($deal->customField[$field->id]) ? 'mt-2' : '' }}">
                                                            <i class="ti ti-circle-plus"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{Str::ucfirst($field->name)}}</strong><br>
                                                            @if ($field->type == 'attachment')
                                                                <a href="{{ !empty($deal->customField[$field->id]) ? get_file($deal->customField[$field->id]) : get_file('Modules/Lead/Resources/assets/upload/default-img.png') }}"
                                                                    target=_blank
                                                                    class="btn btn-md p-0"
                                                                    data-bs-toggle="tooltip" title="{{ __('Preview') }}">
                                                                    <h5 class="text-warning">{{ __('Preview Attachment') }}
                                                                        <i class="ti ti-arrow-up-right"></i>
                                                                    </h5>
                                                                </a>
                                                            @else
                                                                @if ($field->type == 'textarea')
                                                                    <div class="description-container" data-key="{{ $key }}">
                                                                        <h5 class="text-warning shortDescription" id="shortDescription{{$key}}">
                                                                            {{ \Illuminate\Support\Str::limit(!empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-', 40) }}
                                                                        </h5>
                                                                        <h5 class="text-warning fullDescription" style="display: none;">
                                                                            {{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}
                                                                        </h5>
                                                                    </div>
                                                                @else
                                                                    <h5 class="text-warning">{{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}</h5>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @elseif($key % 4 === 3)
                                                        <div class="theme-avtar bg-danger {{ $field->type == 'textarea' && !empty($deal->customField[$field->id]) ? 'mt-2' : '' }}">
                                                            <i class="ti ti-circle-plus"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{Str::ucfirst($field->name)}}</strong><br>
                                                            @if ($field->type == 'attachment')
                                                                <a href="{{ !empty($deal->customField[$field->id]) ? get_file($deal->customField[$field->id]) : get_file('Modules/Lead/Resources/assets/upload/default-img.png') }}"
                                                                    target=_blank
                                                                    class="btn btn-md p-0"
                                                                    data-bs-toggle="tooltip" title="{{ __('Preview Attachment') }}">
                                                                    <h5 class="text-danger">{{ __('Preview Attachment') }}
                                                                        <i class="ti ti-arrow-up-right"></i>
                                                                    </h5>
                                                                </a>
                                                            @else
                                                                @if ($field->type == 'textarea')
                                                                    <div class="description-container" data-key="{{ $key }}">
                                                                        <h5 class="text-danger shortDescription" id="shortDescription{{$key}}">
                                                                            {{ \Illuminate\Support\Str::limit(!empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-', 40) }}
                                                                        </h5>
                                                                        <h5 class="text-danger fullDescription" style="display: none;">
                                                                            {{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}
                                                                        </h5>
                                                                    </div>
                                                                @else
                                                                    <h5 class="text-danger">{{ !empty($deal->customField[$field->id]) ? $deal->customField[$field->id] : '-' }}</h5>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <?php
                        $tasks = $deal->tasks;
                        $products = $deal->products();
                        $sources = $deal->sources();
                        $calls = $deal->calls;
                        $emails = $deal->emails;
                        ?>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-auto mb-3 mb-sm-0">
                                                <small class="m-b-20">{{ __('Task') }}</small>
                                                <h3 class="text-dark">{{ count($tasks) }}</h3>
                                            </div>
                                            <div class="col-auto">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-subtask text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col">
                                                <small class="m-b-20">{{ __('Product') }}</small>
                                                <h3 class="text-dark">{{ count($products) }}</h3>
                                            </div>
                                            <div class="col-auto">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-shopping-cart text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col">
                                                <small class="m-b-20">{{ __('Source') }}</small>
                                                <h3 class="text-dark">{{ count($sources) }}</h3>
                                            </div>
                                            <div class="col-auto">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-social text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col">
                                                <small class="m-b-20">{{ __('Files') }}</small>
                                                <h3 class="text-dark">{{ count($deal->files) }}</h3>
                                            </div>
                                            <div class="col-auto">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-file text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tasks">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card table-card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5>{{ __('Tasks') }}</h5>
                                                    </div>
                                                    @permission('deal edit')
                                                        <div class="float-end">
                                                            <a class="btn btn-sm btn-primary float-end "
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Add Task') }}"
                                                                data-url="{{ route('deals.tasks.create', $deal->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Add Task') }}">
                                                                <i class="ti ti-plus text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 table-border-style bg-none"
                                                style ="height:300px;overflow: auto;">
                                                <div class="">
                                                    <table class="table align-items-center mb-0" id="tasks">
                                                        <tbody class="list">
                                                            @foreach ($tasks as $task)
                                                                <tr>
                                                                    <td>
                                                                        <div
                                                                            class="custom-control custom-switch form-check form-switch mb-2">
                                                                            @permission('deal task edit')
                                                                                <input type="checkbox"
                                                                                    class="form-check-input task-checkbox"
                                                                                    role="switch"
                                                                                    id="task_{{ $task->id }}"
                                                                                    @if ($task->status) checked="checked" @endpermission type="checkbox" value="{{ $task->status }}" data-url="{{ route('deals.tasks.update_status', [$deal->id, $task->id]) }}"/>

                                                                            @endpermission
                                                                            <label for="task_{{ $task->id }}" class="custom-control-label ml-4 @if ($task->status) strike @endif">
                                                                                <h6
                                                                                    class="media-title text-sm form-check-label">
                                                                                    {{ $task->name }}
                                                                                    @if ($task->status)
                                                                                        <div
                                                                                            class="badge rounded p-2 px-3 bg-success mb-1">
                                                                                            {{ __(Modules\Lead\Entities\DealTask::$status[$task->status]) }}
                                                                                        </div>
                                                                                    @else
                                                                                        <div
                                                                                            class="badge rounded p-2 px-3 bg-warning mb-1">
                                                                                            {{ __(Modules\Lead\Entities\DealTask::$status[$task->status]) }}
                                                                                        </div>
                                                                                    @endif
                                                                                </h6>
                                                                                <div class="text-xs text-muted">
                                                                                    {{ __(Modules\Lead\Entities\DealTask::$priorities[$task->priority]) }}
                                                                                    -
                                                                                    <span
                                                                                        class="text-primary">{{ company_datetime_formate($task->date . ' ' . $task->time) }}</span>
                                                                                </div>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="Action text-end">
                                                                            <span>
                                                                                @permission('deal task edit')
                                                                                    <div class="action-btn bg-info ms-2">
                                                                                        <a data-size="md"
                                                                                            data-url="{{ route('deals.tasks.edit', [$deal->id, $task->id]) }}"
                                                                                            data-ajax-popup="true"
                                                                                            data-title="{{ __('Edit Task') }}"
                                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ __('Edit Task') }}"><i
                                                                                                class="ti ti-pencil text-white"></i></a>
                                                                                    </div>
                                                                                @endpermission
                                                                                @permission('deal task delete')
                                                                                    <div class="action-btn bg-danger ms-2">
                                                                                        {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['deals.tasks.destroy', $deal->id, $task->id],
                                                                                            'id' => 'delete-form-' . $task->id,
                                                                                        ]) !!}
                                                                                        <a href="#!"
                                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ __('Delete Task') }}">
                                                                                            <span class="text-white"> <i
                                                                                                    class="ti ti-trash"></i></span></a>
                                                                                        {!! Form::close() !!}
                                                                                    </div>
                                                                                @endpermission
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Products', $permission))
                            <div id="products-users">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card table-card deal-card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5>{{ __('Users') }}</h5>
                                                        </div>
                                                        @permission('deal edit')
                                                            <div class="float-end">
                                                                <a class="btn btn-sm btn-primary float-end "
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Add User') }}"
                                                                    data-url="{{ route('deals.users.edit', $deal->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Add User') }}">
                                                                    <i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    </div>
                                                </div>
                                                <div class="card-body p-0 table-border-style bg-none "
                                                    style ="height:300px;overflow: auto;">
                                                    <div class="">
                                                        <table class="table align-items-center mb-0">
                                                            <tbody class="list">
                                                                @foreach ($deal->users as $user)
                                                                    <tr>
                                                                        <td>
                                                                            <a @if ($user->avatar) href="{{ get_file($user->avatar) }}" @else href="{{ get_file('uploads/users-avatar/avatar.png') }}" @endif
                                                                                target="_blank">
                                                                                <img @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('uploads/users-avatar/avatar.png') }}" @endif
                                                                                    width="30"
                                                                                    class="avatar-sm rounded-circle">
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <span class="number-id">{{ $user->name }}</span>
                                                                        </td>
                                                                        @permission('deal edit')
                                                                            <td>
                                                                                @if ($deal->created_by == \Auth::user()->id)
                                                                                    <div class="action-btn bg-danger ">
                                                                                        {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['deals.users.destroy', $deal->id, $user->id],
                                                                                            'id' => 'delete-form-' . $deal->id,
                                                                                        ]) !!}
                                                                                        <a href="#!"
                                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ __('Delete User') }}">
                                                                                            <span class="text-white"> <i
                                                                                                    class="ti ti-trash"></i></span></a>
                                                                                        {!! Form::close() !!}
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                        @endpermission
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card table-card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5>{{ __('Products') }}</h5>
                                                        </div>
                                                        @permission('deal edit')
                                                            <div class="float-end">
                                                                <a class="btn btn-sm btn-primary float-end "
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Add Products') }}"
                                                                    data-url="{{ route('deals.products.edit', $deal->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Add Products') }}">
                                                                    <i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0 table-border-style bg-none "
                                                    style ="height:334px;overflow: auto;">
                                                    <div class="">
                                                        <table class="table align-items-center mb-0" id="products">
                                                            <tbody class="list">
                                                                @if ($products)
                                                                    @foreach ($products as $product)
                                                                        <tr>
                                                                            <td>
                                                                                <?php
                                                                                if (check_file($product->image) == false) {
                                                                                    $path = asset('Modules/ProductService/Resources/assets/image/img01.jpg');
                                                                                } else {
                                                                                    $path = get_file($product->image);
                                                                                }
                                                                                ?>
                                                                                <a href="{{ $path }}"
                                                                                    target="_blank">
                                                                                    <img width="50"
                                                                                        src="{{ $path }}">
                                                                                </a>
                                                                            </td>
                                                                            @if (module_is_active('ProductService'))
                                                                                <td>
                                                                                    <span
                                                                                        class="number-id">{{ $product->name }}
                                                                                    </span> (<span
                                                                                        class="text-muted">{{ currency_format_with_sym($product->sale_price) }}</span>)
                                                                                </td>
                                                                            @endif
                                                                            @permission('deal edit')
                                                                                <td class="text-end">
                                                                                    <div class="action-btn bg-danger ">
                                                                                        {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['deals.products.destroy', $deal->id, $product->id],
                                                                                            'id' => 'delete-form-' . $deal->id,
                                                                                        ]) !!}
                                                                                        <a href="#!"
                                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ __('Delete Product') }}">
                                                                                            <span class="text-white"> <i
                                                                                                    class="ti ti-trash"></i></span></a>
                                                                                        {!! Form::close() !!}
                                                                                    </div>
                                                                                </td>
                                                                            @endpermission
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td>{{ __('No Product Found.!') }}</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Sources', $permission))
                            <div id="sources-emails">
                                <div class="col-12">
                                    <div class="row ">
                                        <div class="col-md-6">
                                            <div class="card table-card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5>{{ __('Sources') }}</h5>
                                                        </div>
                                                        @permission('deal edit')
                                                            <div class="float-end">
                                                                <a class="btn btn-sm btn-primary float-end "
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Add Sources') }}"
                                                                    data-url="{{ route('deals.sources.edit', $deal->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Add Sources') }}">
                                                                    <i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0 table-border-style bg-none"
                                                    style ="height:300px;overflow: auto;">
                                                    <div class="">
                                                        <table class="table align-items-center mb-0" id="sources">
                                                            <tbody class="list">
                                                                @if ($sources)
                                                                    @foreach ($sources as $source)
                                                                        <tr>
                                                                            <td>
                                                                                <span
                                                                                    class="text-dark">{{ $source->name }}</span>
                                                                            </td>
                                                                            <td class="text-end">
                                                                                @permission('deal edit')
                                                                                    <div class="action-btn bg-danger ">
                                                                                        {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['deals.sources.destroy', $deal->id, $source->id],
                                                                                            'id' => 'delete-form-' . $deal->id,
                                                                                        ]) !!}
                                                                                        <a href="#!"
                                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                            data-bs-toggle="tooltip"
                                                                                            data-bs-placement="top"
                                                                                            title="{{ __('Delete Source') }}">
                                                                                            <span class="text-white"> <i
                                                                                                    class="ti ti-trash"></i></span>
                                                                                        </a>
                                                                                        {!! Form::close() !!}
                                                                                    </div>
                                                                                @endpermission
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card table-card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5>{{ __('Email') }}</h5>
                                                        </div>
                                                        @permission('lead email create')
                                                            <div class="float-end">
                                                                <a class="btn btn-sm btn-primary float-end "
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Add Email') }}"
                                                                    data-url="{{ route('deals.emails.create', $deal->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Add Email') }}">
                                                                    <i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    </div>
                                                </div>
                                                <div class="card-body table-border-style bg-none"
                                                    style ="height:300px;overflow: auto;">
                                                    <ul class="list-unstyled list-unstyled-border">
                                                        @foreach ($emails as $email)
                                                            <li class="media mb-3">
                                                                <div style="margin-right: 10px;">
                                                                    <a @if ($user->avatar) href="{{ get_file($user->avatar) }}" @else href="{{ asset('custom/img/avatar/avatar-1.png') }}" @endif
                                                                        target="_blank">
                                                                        <img @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ asset('custom/img/avatar/avatar-1.png') }}" @endif
                                                                            width="30" class="avatar-sm rounded-circle">
                                                                    </a>
                                                                </div>
                                                                <div class="media-body">
                                                                    <div class="mt-0 mb-1 font-weight-bold text-sm">
                                                                        {{ $email->subject }}
                                                                        <small
                                                                            class="float-right">{{ $email->created_at->diffForHumans() }}</small>
                                                                    </div>
                                                                    <div class="text-xs">{{ $email->to }}</div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="discussion-notes">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5>{{ __('Discussion') }}</h5>
                                                    </div>
                                                    <div class="float-end">
                                                        <a class="btn btn-sm btn-primary float-end " data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Add Message') }}"
                                                            data-url="{{ route('deals.discussions.create', $deal->id) }}"
                                                            data-ajax-popup="true" data-title="{{ __('Add Message') }}">
                                                            <i class="ti ti-plus text-white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body" style="height:330px;overflow: auto;">
                                                <ul class="list-unstyled list-unstyled-border">
                                                    @foreach ($deal->discussions as $discussion)
                                                        <li class="media">
                                                            <div style="margin-right: 10px;">

                                                                <a @if ($discussion->user->avatar) href="{{ get_file($discussion->user->avatar) }}" @else href="{{ get_file('uploads/users-avatar/avatar.png') }}" @endif
                                                                    target="_blnak">
                                                                    <img @if ($discussion->user->avatar) src="{{ get_file($discussion->user->avatar) }}" @else src="{{ get_file('uploads/users-avatar/avatar.png') }}" @endif
                                                                        width="50" class="avatar-sm rounded-circle">
                                                                </a>
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="mt-0 mb-1 font-weight-bold text-sm fw-bold">
                                                                    {{ $discussion->user->name }}
                                                                    <small>{{ $discussion->user->type }}</small> <small
                                                                        class="float-end">{{ $discussion->created_at->diffForHumans() }}</small>
                                                                </div>
                                                                <div class="text-xs"> {{ $discussion->comment }}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5>{{__('Notes')}}</h5>
                                                    <div class="col-6 text-end">
                                                        @if (module_is_active('AIAssistant'))
                                                            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'deal_notes','module'=>'Lead'])
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body" style="height:330px;">
                                                <textarea name="description"
                                                    class="form-control summernote {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required
                                                    id="deal-summernote">{!! $deal->notes !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!Auth::user()->hasRole('super addmin') || in_array('Client View Files', $permission))
                            <div id="files">
                                <div class="row pt-2">
                                    <div class="col-12">
                                        <div class="card table-card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5>{{ __('Files') }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body bg-none">
                                                    <div class="col-md-12 dropzone browse-file" id="dropzonewidget2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (!Auth::user()->hasRole('super addmin'))
                            <div id="clients">
                                <div class="row pt-2">
                                    <div class="col-12">
                                        <div class="card table-card">
                                            <div class="card-header">
                                                <h5>{{ __('Clients') }}
                                                    @permission('deal edit')
                                                        <a data-size="md" class="btn btn-sm btn-primary float-end "
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Add Client') }}"
                                                            data-url="{{ route('deals.clients.edit', $deal->id) }}"
                                                            data-ajax-popup="true" data-title="{{ __('Add Client') }}">
                                                            <i class="ti ti-plus text-white"></i>
                                                        </a>
                                                    @endpermission
                                                </h5>
                                            </div>
                                            <div class="card-body table-border-style">
                                                <div class="">
                                                    <table class="table mb-0 pc-dt-simple" id="client_call">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Avatar') }}</th>
                                                                <th>{{ __('Name') }}</th>
                                                                <th>{{ __('Email') }}</th>
                                                                @permission('deal edit')
                                                                    <th>{{ __('Action') }}</th>
                                                                @endpermission
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($deal->clients as $client)
                                                                <tr>
                                                                    <td>
                                                                        <a href="@if ($client->avatar) {{ get_file($client->avatar) }} @else {{ get_file('uploads/users-avatar/avatar.png') }} @endif"
                                                                            target="_blnak">
                                                                            <img src="@if ($client->avatar) {{ get_file($client->avatar) }} @else {{ get_file('uploads/users-avatar/avatar.png') }} @endif"
                                                                                class="rounded-circle mr-1" width="50">
                                                                    </td>
                                                                    </a>

                                                                    <td>{{ $client->name }}</td>
                                                                    <td>{{ $client->email }}</td>
                                                                    <td class="">
                                                                        @permission('deal edit')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'route' => ['deals.clients.destroy', $deal->id, $client->id],
                                                                                    'id' => 'delete-form-' . $deal->id,
                                                                                ]) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="{{ __('Delete Client') }}">
                                                                                    <span class="text-white"> <i
                                                                                            class="ti ti-trash"></i></span>
                                                                                    {!! Form::close() !!}
                                                                            </div>
                                                                        @endpermission
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!Auth::user()->hasRole('super admin'))
                            <div id="calls">
                                <div class="row pt-2">
                                    <div class="col-12">
                                        <div class="card table-card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5>{{ __('Calls') }}</h5>
                                                    </div>
                                                    @permission('deal call create')
                                                        <div class="float-end">
                                                            <a data-size="lg" class="btn btn-sm btn-primary float-end "
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Add Call') }}"
                                                                data-url="{{ route('deals.calls.create', $deal->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Add Call') }}">
                                                                <i class="ti ti-plus text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                </div>
                                            </div>
                                            <div class=" card-body table-border-style">
                                                <div class="">
                                                    <table class="table mb-0 pc-dt-simple" id="deal_call">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Subject') }}</th>
                                                                <th>{{ __('Call Type') }}</th>
                                                                <th>{{ __('Duration') }}</th>
                                                                <th>{{ __('User') }}</th>
                                                                <th width="14%">{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($calls as $call)
                                                                <tr>
                                                                    <td>{{ $call->subject }}</td>
                                                                    <td>{{ ucfirst($call->call_type) }}</td>
                                                                    <td>{{ $call->duration }}</td>
                                                                    <td>{{ !empty($call->getDealCallUser->name) ? $call->getDealCallUser->name : '' }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        @permission('deal call edit')
                                                                            <div class="action-btn bg-info ms-2">
                                                                                <a data-size="lg"
                                                                                    data-url="{{ URL::to('deals/' . $deal->id . '/call/' . $call->id . '/edit') }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-title="{{ __('Edit Call') }}"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="{{ __('Edit Call') }}"><i
                                                                                        class="ti ti-pencil text-white"></i></a>
                                                                            </div>
                                                                        @endpermission
                                                                        @permission('deal call delete')
                                                                            <div class="action-btn bg-danger ms-2">
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'route' => ['deals.calls.destroy', $deal->id, $call->id],
                                                                                    'id' => 'delete-form-' . $deal->id,
                                                                                ]) !!}
                                                                                <a href="#!"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="{{ __('Delete Deal') }}">
                                                                                    <span class="text-white"> <i
                                                                                            class="ti ti-trash"></i></span>
                                                                                    {!! Form::close() !!}
                                                                            </div>
                                                                        @endpermission
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (!Auth::user()->hasRole('super admin') || in_array('Client Deal Activity', $permission))
                            <div id="activity">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Activity') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="height:350px !important;overflow-y: scroll;">
                                                <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                                    @foreach ($deal->activities as $activity)
                                                        <li class="list-group-item card mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="fas {{ $activity->logIcon() }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <h6 class="m-0">{!! $activity->getRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
