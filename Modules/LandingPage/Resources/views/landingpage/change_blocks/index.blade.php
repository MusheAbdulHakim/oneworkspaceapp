@extends('layouts.main')

@section('page-title')
    {{ __('Landing Page') }}
@endsection

@section('page-breadcrumb')
    {{__('Landing Page')}}
@endsection

@section('page-action')
    <div data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Qr Code') }}">
        <a class="btn btn-sm btn-primary btn-icon" data-bs-toggle="modal"  data-bs-target="#qrcodeModal" id="download-qr"
        target="_blanks" >
        <span class="text-white"><i class="fa fa-qrcode"></i></span>
    </a>
    <a class="btn btn-sm btn-primary btn-icon ml-0" data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-original-title="{{ __('Preview') }}" href="{{ url('/') }}" target="-blank" ><span
    class="text-white"><i class="ti ti-eye"></i></span></a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('landingpage::landingpage.sections')
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5>{{ __('Change Blocks') }}</h5>
                        </div>
                        <div id="p1" class="col-auto text-end text-primary h3">
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                <div class="border">
                    <form method="post" action="{{ route('landing.change.blocks.store') }}">
                            @csrf
                            <table class="table table-hover bug-stages">
                                <thead>
                                    <th>
                                        <div data-toggle="tooltip" data-placement="left"
                                            data-title="{{ __('Drag Stage to Change Order') }}" data-original-title=""
                                            title="">
                                            <i class="fas fa-crosshairs"></i>
                                        </div>
                                    </th>
                                    <th>{{ __('Section') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody id="sortable-table">
                                    <input type="hidden" name="landing_page_section_sequence" id="sequence-input" value="{{ $settings['landing_page_section_sequence'] }}">
                                    @foreach (json_decode( $settings['landing_page_section_sequence'], true) as $key => $value)
                                        <tr>
                                            <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                            <td><h6>{{ __($stages[$value]) }}</h6></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{__('On/Off')}}:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="checkbox" class="form-check-input input-primary" name="{{ $value }}"
                                                            id="{{ $value }}" @if(isset($settings[$value]) && $settings[$value] == 'on')  checked="checked" @endif >
                                                        <label class="form-check-label" for="customswitchv1-1"></label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer row">
                                <div class="text-sm col-6 pt-2">
                                    {{__('Note : You can easily change order of Sections using drag & drop.')}}
                                </div>
                                <div class="text-end col-6 pt-2">
                                    <button class="btn-submit btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script src="{{ asset('Modules/Taskly/Resources/assets/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('Modules/Taskly/Resources/assets/js/repeater.js')}}"></script>
<script src="{{ asset('Modules/Taskly/Resources/assets/js/colorPick.js')}}"></script>
<script src="{{asset('assets/js/pages/wow.min.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Sortable(document.getElementById('sortable-table'), {
            handle: '.sort-handler',
            onUpdate: function (event) {
                updateSequence();
            }
        });

        function updateSequence() {
            var sequence = [];
            var rows = document.getElementById('sortable-table').getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var key = rows[i].querySelector('input[type="checkbox"]').getAttribute('name');
                sequence.push(key);
            }
            console.log(sequence);
            document.getElementById('sequence-input').value = JSON.stringify(sequence);
        }
    });
</script>
   
@endpush
