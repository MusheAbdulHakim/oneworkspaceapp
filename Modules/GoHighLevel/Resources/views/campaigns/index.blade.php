@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Campaigns')}}
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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($campaigns))
                                    @foreach($campaigns as $i => $campaign)
                                    @if(!empty($campaign) && is_array($campaign))
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$campaign['name']}}</td>
                                        <td>{{$campaign['status']}}</td>
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

