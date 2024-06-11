@permission('sidemenubuilder manage')
    @foreach ($module as $modules)
        @php
            $sub_menus = Modules\SideMenuBuilder\Entities\SideMenuBuilder::where('parent_id', $modules->id)
                ->orderBy('position')
                ->get();
            $menus = Modules\SideMenuBuilder\Entities\SideMenuBuilder::with('childs')
                ->where('parent_id', '=', $modules->id)
                ->where('menu_type', '=', 'submenu')
                ->whereIn('name', $modules)
                ->orderBy('position')
                ->get();
            $main_links = json_decode($modules->link, true);
        @endphp

        <li class="dash-item dash-hasmenu">
            @if (count($sub_menus) > 0)
                <a href="#" class="dash-link">
                    <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                    <span class="dash-mtext">{{ __($modules->name) }}</span>
                    <span class="dash-arrow">
                        <i data-feather="chevron-right"></i>
                    </span>
                </a>
                <ul class="dash-submenu" style="display: none;">
                    @foreach ($sub_menus as $item)
                        @php
                            $submenu = json_decode($item->link, true);
                        @endphp
                        @if ($item->menu_type == 'submenu')
                            <li class="dash-item">
                                @if ($item->window_type == 'same_window')
                                    <a class="dash-link"
                                        href="{{ isset($submenu['href'][0]) && isset($submenu['type'][0]) ? $submenu['href'][0] . $submenu['type'][0] : $item->link }}">{{ __($item->name) }}
                                    </a>
                                @elseif($item->window_type == 'new_window')
                                    <a class="dash-link"
                                        href="{{ isset($submenu['href'][0]) && isset($submenu['type'][0]) ? $submenu['href'][0] . $submenu['type'][0] : $item->link }}"
                                        target="_blank">{{ __($item->name) }}
                                    </a>
                                @else
                                    <a class="dash-link"
                                        href="{{ route('sidemenubuilder.iframe', $item->id) }}">{{ __($item->name) }}
                                    </a>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            @else
                @if ($modules->window_type == 'same_window')
                    @if ($modules->link_type == 'external')
                        @if ($modules->menu_type == 'mainmenu')
                            <a href="{{ isset($main_links['href'][0]) && isset($main_links['type'][0]) ? $main_links['href'][0] . $main_links['type'][0] : $modules->link }}"
                                class="dash-link">
                                <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                                <span class="dash-mtext">{{ __($modules->name) }}</span>
                            </a>
                        @endif
                    @else
                        @if ($modules->menu_type == 'mainmenu')
                            <a href="{{ isset($main_links['href'][0]) && isset($main_links['type'][0]) ? $main_links['href'][0] . $main_links['type'][0] : $modules->link }}"
                                class="dash-link">
                                <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                                <span class="dash-mtext">{{ __($modules->name) }}</span>
                            </a>
                        @endif
                    @endif
                @elseif($modules->window_type == 'new_window')
                    @if ($modules->link_type == 'external')
                        @if ($modules->menu_type == 'mainmenu')
                            <a href="{{ isset($main_links['href'][0]) && isset($main_links['type'][0]) ? $main_links['href'][0] . $main_links['type'][0] : $modules->link }}"
                                class="dash-link" target="_blank">
                                <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                                <span class="dash-mtext">{{ __($modules->name) }}</span>
                            </a>
                        @endif
                    @else
                        @if ($modules->menu_type == 'mainmenu')
                            <a href="{{ isset($main_links['href'][0]) && isset($main_links['type'][0]) ? $main_links['href'][0] . $main_links['type'][0] : $modules->link }}"
                                class="dash-link" target="_blank">
                                <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                                <span class="dash-mtext">{{ __($modules->name) }}</span>
                            </a>
                        @endif
                    @endif
                @else
                    @if ($modules->menu_type == 'mainmenu')
                        <a href="{{ route('sidemenubuilder.iframe', $modules->id) }}" class="dash-link">
                            <span class="dash-micon"><i class="{{ $modules->icon }}"></i></span>
                            <span class="dash-mtext">{{ __($modules->name) }}</span>
                        </a>
                    @endif
                @endif
            @endif
        </li>
    @endforeach
@endpermission
