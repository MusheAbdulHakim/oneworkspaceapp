
<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{route('pipelines.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('pipelines*') ? 'active' : '')}}">{{__('Pipelines')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('lead-stages.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('lead-stages*') ? 'active' : '')}}">{{__('Lead Stages')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('deal-stages.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('deal-stages*') ? 'active' : '')}}">{{__('Deal Stages')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('labels.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('labels*') ? 'active' : '')}}">{{__('Label')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('sources.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('sources*') ? 'active' : '')}}">{{__('Sources')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
