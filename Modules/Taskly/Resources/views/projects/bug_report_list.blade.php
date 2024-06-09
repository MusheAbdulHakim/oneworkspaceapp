@extends('layouts.main')
@section('page-title')
    {{ __('Task Board') }}
@endsection
@section('title')
    {{ __('Task Board') }}
@endsection
@section('page-breadcrumb')
    {{ __('Project') }},{{ __('Project Details') }},{{ __('Task Board') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('projects.bug.report', [$project->id]) }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Kanban View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>

        @permission('task create')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Task') }}" data-url="{{ route('projects.bug.report.create', [$project->id]) }}"
                data-toggle="tooltip" title="{{ __('Add Task') }}"><i class="ti ti-plus"></i></a>
        @endpermission
        <a href="{{ route('projects.show', [$project->id]) }}" class="btn-submit btn btn-sm btn-primary"
            data-toggle="tooltip" title="{{ __('Back') }}">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection
@section('filter')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Stage') }}</th>
                                    <th>{{ __('Assigned User') }}</th>
                                    @if (Laratrust::hasPermission('bug show') || Laratrust::hasPermission('bug edit') || Laratrust::hasPermission('bug delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stages as $stage)
                                    @foreach ($stage->bugs as $bug)
                                        <tr>
                                            <td>
                                                <a href="#"
                                                    data-url="{{ route('projects.bug.report.show', [$bug->project_id, $bug->id]) }}"
                                                    class="" data-size="lg" data-ajax-popup="true"
                                                    data-title="{{ __('View') }}">{{ $bug->title }}</a>
                                            </td>
                                            <td>
                                                <span class="budget">{{ $bug->priority }}</span>
                                            </td>
                                            <td>
                                                <span class="budget">{{ $bug->stage->name }}</span>
                                            </td>
                                            <td>
                                                <span class="col-sm-12"><span
                                                        class="text-m">{{ ucfirst(!empty($bug->user) ? $bug->user->name : '-') }}</span></span>
                                            </td>
                                            @if (Laratrust::hasPermission('bug show') || Laratrust::hasPermission('bug edit') || Laratrust::hasPermission('bug delete'))
                                                <td class="text-end">
                                                    @permission('bug show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a data-size="lg"
                                                                data-url="{{ route('projects.bug.report.show', [$bug->project_id, $bug->id]) }}"
                                                                data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                                data-ajax-popup="true" data-title="{{ __('View') }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('bug edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a data-ajax-popup="true" data-size="lg"
                                                                data-url="{{ route('projects.bug.report.edit', [$bug->project_id, $bug->id]) }}"
                                                                class="btn btn-sm d-inline-flex align-items-center text-white "
                                                                data-bs-toggle="tooltip" data-title="{{ __('Task Edit') }}"
                                                                title="{{ __('Edit') }}"><i class="ti ti-pencil"></i></a>

                                                        </div>
                                                    @endpermission
                                                    @permission('bug delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['projects.bug.report.destroy', $bug->project_id, $bug->id]]) !!}
                                                            <a href="#!"
                                                                class="btn btn-sm   align-items-center text-white show_confirm"
                                                                data-bs-toggle="tooltip" title='Delete'>
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endpermission
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@if ($project)
    @push('scripts')
        <!-- third party js -->
        <script>
            ! function(a) {
                "use strict";
                var t = function() {
                    this.$body = a("body")
                };
                t.prototype.init = function() {
                    a('[data-toggle="dragula"]').each(function() {
                        var t = a(this).data("containers"),
                            n = [];
                        if (t)
                            for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                        else n = [a(this)[0]];
                        var r = a(this).data("handleclass");
                        r ? dragula(n, {
                            moves: function(a, t, n) {
                                return n.classList.contains(r)
                            }
                        }) : dragula(n).on('drop', function(el, target, source, sibling) {
                            var sort = [];
                            $("#" + target.id + " > div").each(function(key) {
                                sort[key] = $(this).attr('id');
                            });
                            var id = el.id;
                            var old_status = $("#" + source.id).data('status');
                            var new_status = $("#" + target.id).data('status');
                            var project_id = '{{ $project->id }}';

                            $("#" + source.id).parents('.card-list').find('.count').text($("#" + source.id +
                                " > div").length);
                            $("#" + target.id).parents('.card-list').find('.count').text($("#" + target.id +
                                " > div").length);
                            $.ajax({
                                url: '{{ route('projects.bug.report.update.order', [$project->id]) }}',
                                type: 'POST',
                                data: {
                                    id: id,
                                    sort: sort,
                                    new_status: new_status,
                                    old_status: old_status,
                                    project_id: project_id,
                                },
                                success: function(data) {}
                            });
                        });
                    })
                }, a.Dragula = new t, a.Dragula.Constructor = t
            }(window.jQuery),
            function(a) {
                "use strict";
                a.Dragula.init()
            }(window.jQuery);
        </script>
        <!-- third party js ends -->
        <script>
            $(document).on('click', '#form-comment button', function(e) {
                var comment = $.trim($("#form-comment textarea[name='comment']").val());
                if (comment != '') {
                    $.ajax({
                        url: $("#form-comment").data('action'),
                        data: {
                            comment: comment
                        },
                        type: 'POST',
                        success: function(data) {
                            data = JSON.parse(data);

                            if (data.user_type == 'Client') {
                                var avatar = "avatar='" + data.client.name + "'";
                                var html = "<li class='row media border-bottom mb-3'>" +
                                    "                    <div class='col-1'>" +
                                    "                       <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img' width='80' " +
                                    avatar +
                                    "                    </div>" +
                                    " alt='" + data.client.name + "'>" +
                                    "                    <div class='col media-body mb-2'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" +
                                    data.client.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                    </div>" +
                                    "                </li>";
                            } else {
                                var avatar = (data.user.avatar) ? "src='{{ asset('') }}/" + data.user
                                    .avatar + "'" : "avatar='" + data.user.name + "'";
                                var html = "<li class='row media border-bottom mb-3'>" +
                                    "                    <div class='col-1'>" +
                                    "                       <img class='mr-3 avatar-sm rounded-circle img-thumbnail hight_img' width='90' " +
                                    avatar +
                                    " alt='" + data.user.name + "'>" +
                                    "                    </div>" +
                                    "                    <div class='col media-body mb-2'>" +
                                    "                        <h5 class='mt-0 mb-1 form-control-label'>" +
                                    data.user.name + "</h5>" +
                                    "                        " + data.comment +
                                    "                    </div>" +
                                    "                    <div class='col text-end mt-2'>" +
                                    "                               <a href='#' class='delete-icon delete-comment action-btn btn-danger  btn btn-sm d-inline-flex align-items-center' data-url='" +
                                    data.deleteUrl + "'>" +
                                    "                                   <i class='ti ti-trash'></i>" +
                                    "                               </a>" +
                                    "                    </div>" +
                                    "            </li>";
                            }
                            $("#comments").prepend(html);
                            LetterAvatar.transform();

                            $("#form-comment textarea[name='comment']").val('');
                            toastrs('{{ __('Success') }}', '{{ __('Comment Added Successfully!') }}',
                                'success');
                        },
                        error: function(data) {
                            toastrs('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}', 'error');
                        }
                    });
                } else {
                    toastrs('{{ __('Error') }}', '{{ __('Please write comment!') }}', 'error');
                }
            });
            $(document).on("click", ".delete-comment", function() {
                if (confirm('{{ __('Are you sure ?') }}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(data) {
                            toastrs('{{ __('Success') }}', '{{ __('Comment Deleted Successfully!') }}',
                                'success');
                            btn.closest('.media').remove();
                        },
                        error: function(data) {
                            data = data.responseJSON;
                            if (data.message) {
                                toastrs('{{ __('Error') }}', data.message, 'error');
                            } else {
                                toastrs('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}',
                                    'error');
                            }
                        }
                    });
                }
            });

            $(document).on('submit', '#form-file', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $("#form-file").data('url'),
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastrs('{{ __('Success') }}', '{{ __('File Upload Successfully!') }}',
                            'success');
                        var delLink = '';

                        if (data.deleteUrl.length > 0) {
                            delLink =
                                "<a href='#' class='delete-icon delete-comment-file action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-comment'  data-url='" +
                                data.deleteUrl + "'>" +
                                "                                        <i class='ti ti-trash'></i>" +
                                "                                    </a>";
                        }

                        var html = "<div class='card mb-1 shadow-none border'>" +
                            "                        <div class='card-body p-3'>" +
                            "                            <div class='row align-items-center'>" +
                            "                                <div class='col-auto'>" +
                            "                                    <div class='avatar-sm'>" +
                            "                                        <span class='avatar-title rounded text-uppercase'>" +
                            "  <img src='{{ asset('') }}/" +
                            data.file +
                            "' width='60px' height='60px' alt='' >" +
                            "                                        </span>" +
                            "                                    </div>" +
                            "                                </div>" +
                            "                                <div class='col pl-0'>" +
                            "                                    <a href='#' class='text-muted form-control-label'>" +
                            data.name + "</a>" +
                            "                                    <p class='mb-0'>" + data.file_size +
                            "</p>" +
                            "                                </div>" +
                            "                                <div class='col-auto'>" +
                            "                                    <a download href='{{ asset('') }}/" +
                            data.file +
                            "' class='action-btn btn-primary  btn btn-sm d-inline-flex align-items-center mx-1'>" +
                            "                                        <i class='ti ti-download'></i>" +
                            "                                    </a>" +
                            delLink +
                            "                                </div>" +
                            "                            </div>" +
                            "                        </div>" +
                            "                    </div>";
                        $("#comments-file").prepend(html);
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('{{ __('Error') }}', data.message, 'error');
                            $('#file-error').text(data.errors.file[0]).show();
                        } else {
                            toastrs('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}', 'error');
                        }
                    }
                });
            });
            $(document).on("click", ".delete-comment-file", function() {
                if (confirm('{{ __('Are you sure ?') }}')) {
                    var btn = $(this);
                    $.ajax({
                        url: $(this).attr('data-url'),
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(data) {
                            toastrs('{{ __('Success') }}', '{{ __('File Deleted Successfully!') }}',
                                'success');
                            btn.closest('.border').remove();
                        },
                        error: function(data) {
                            data = data.responseJSON;
                            if (data.message) {
                                toastrs('{{ __('Error') }}', data.message, 'error');
                            } else {
                                toastrs('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}',
                                    'error');
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endif
