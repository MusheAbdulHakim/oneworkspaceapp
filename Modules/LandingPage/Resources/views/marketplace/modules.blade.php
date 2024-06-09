@php
    $modules = getshowModuleList();
@endphp
<div class="card align-middle p-3">
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        @foreach ($modules as $module)
            <li class="nav-item px-1">
                <a class="nav-link text-capitalize {{ ( $slug == ($module)) ? ' active' : '' }} " href="{{ route('marketplace.index', ($module)) }}">{{ Module_Alias_Name($module) }}</a>
            </li>
        @endforeach
    </ul>
</div>