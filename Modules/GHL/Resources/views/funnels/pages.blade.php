<div class="modal-body">
    <h6 class="sub-title">{{__('Funnel Pages')}}</h6>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="funnel_pages_table" class="table table-bordered dt-responsive pc-dt-simple">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Last Updated</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($pages))
                                @foreach($pages as $i => $page)
                                @if(!empty($page) && is_array($page))
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$page['name']}}</td>
                                    <td>{{\Carbon\Carbon::parse($page['updatedAt'])->format('d M, Y') ?? ''}}</td>
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
</div>
