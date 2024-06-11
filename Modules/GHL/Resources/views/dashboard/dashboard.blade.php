@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Dashboard')}}
@endsection

@section('page-breadcrumb')
    {{ __('GoHighLevel')}}
@endsection


@section('content')
<x-active-modules />
    <div class="row">
        <div class="col-md-4 col-sm-6">
          <a href="{{ route('ghl.contacts') }}">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar p-50 mb-1">
                      <div class="avatar-content">
                          <i data-feather="users"></i>
                      </div>
                    </div>
                    <h2 class="fw-bolder">{{count($contacts) ?? 0}}</h2>
                    <p class="card-text">Contacts</p>
                </div>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-sm-6">
          <a href="{{ route('ghl.invoices') }}">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar p-50 mb-1">
                      <div class="avatar-content">
                          <i data-feather="page"></i>
                      </div>
                    </div>
                    <h2 class="fw-bolder">{{count($invoices) ?? 0}}</h2>
                    <p class="card-text">Invoices</p>
                </div>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card text-center">
              <div class="card-body">
                  <div class="avatar p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="page"></i>
                    </div>
                  </div>
                  <h2 class="fw-bolder">{{$funnels['count'] ?? 0}}</h2>
                  <p class="card-text">Funnels</p>
              </div>
          </div>
        </div>
    </div>
    <hr>
    <div class="row">
        @if(!empty($calendars) && count($calendars) > 0)
        <div class="col-12">
            <div class="card">
                <h6 class="card-header">GHL Calendars</h6>
                <div class="p-2">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table id="calendars_table" class="table mb-0 pc-dt-simple">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Calendar Type</th>
                                    </tr>
                                </thead>
                            </table>
                            <tbody>
                                @foreach($calendars as $i => $calendar)
                                <tr>
                                    <th scope="row">{{++$i}}</th>
                                    <td>{{$calendar['name']}}</td>
                                    <td>{{$calendar['calendarType']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        (function() {

        });
    </script>
@endpush



