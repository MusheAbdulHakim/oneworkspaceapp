@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Contacts')}}
@endsection

@section('page-breadcrumb')
    {{ __('GoHighLevel')}}
@endsection


@section('content')

    <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Country</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($contacts))
                                    @foreach($contacts as $i => $contact)
                                    @if(!empty($contact) && is_array($contact))
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$contact['contactName']}}</td>
                                        <td>{{$contact['email']}}</td>
                                        <td>{{$contact['type']}}</td>
                                        <td>{{$contact['country']}}</td>
                                        <td>
                                            @php
                                                $date = $contact['dateAdded'] ?? null;
                                                if(!empty($date)){
                                                    $date = \Carbon\Carbon::parse($date)->format('d M, Y');
                                                }
                                            @endphp
                                            {{$date}}
                                        </td>
                                        <td>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-url="{{ route('gohighlevel.contact.appointments',($contact['id'])) }}"
                                                    data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Appointments')}}">
                                                    <span class="text-white"><i class="ti ti-calendar"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-url="{{ route('gohighlevel.contact.notes',($contact['id'])) }}"
                                                    data-ajax-popup="true" data-size="lg"  data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Notes')}}">
                                                    <span class="text-white"><i class="ti ti-note"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-url="{{ route('gohighlevel.contact.tasks',($contact['id'])) }}"
                                                    data-ajax-popup="true" data-size="lg"  data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Task')}}">
                                                    <span class="text-white"><i class="ti ti-clock"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('scripts')
    <script>
        (function() {

        });
    </script>
@endpush

