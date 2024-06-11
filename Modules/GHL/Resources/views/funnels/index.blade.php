@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Funnels')}}
@endsection

@section('page-breadcrumb')
    {{ __('Funnels')}}
@endsection


@section('content')

    <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="funnels_table" class="table table-bordered dt-responsive pc-dt-simple">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($funnels))
                                    @foreach($funnels as $i => $funnel)
                                    @if(!empty($funnel) && is_array($funnel))
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$funnel['name']}}</td>
                                        <td>{{$funnel['url']}}</td>
                                        <td>{{\Carbon\Carbon::parse($funnel['updatedAt'])->format('d M, Y') ?? ''}}</td>
                                        <td>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-url="{{ route('ghl.funnel.pages',['funnelId' => $funnel['_id']]) }}"
                                                    data-ajax-popup="true" data-size="lg"  data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Pages')}}">
                                                    <span class="text-white"><i class="ti ti-file"></i>
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

