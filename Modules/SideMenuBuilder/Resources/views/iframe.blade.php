@extends('layouts.main')
@php
    $menu_link = json_decode($sideMenuBuilder->link, true);
    
    $player = 'none';
    if (str_contains($sideMenuBuilder->link, 'youtube') || str_contains($sideMenuBuilder->link, 'youtu.be')) {
        $player = 'youtube';
        if (strpos($sideMenuBuilder->link, 'src') !== false) {
            preg_match('/src="([^"]+)"/', $sideMenuBuilder->link, $match);
            $url = $match[1];
            $video_url = str_replace('https://www.youtube.com/embed/', '', $url);
        } else {
            $video_url = str_replace('https://youtu.be/', '', str_replace('https://www.youtube.com/watch?v=', '', $sideMenuBuilder->link));
        }
    } elseif (str_contains($sideMenuBuilder->link, 'vimeo')) {
        $player = 'vimeo';
        if (strpos($sideMenuBuilder->link, 'src') !== false) {
            preg_match('/src="([^"]+)"/', $sideMenuBuilder->link, $match);
            $url = $match[1];
            $video_url = str_replace('https://player.vimeo.com/video/', '', $url);
        } else {
            $video_url = str_replace('https://vimeo.com/', '', $sideMenuBuilder->link);
        }
    } else {
        $video_url = $sideMenuBuilder->link;
    }
    
    $menu_link = json_decode($sideMenuBuilder->link, true);
@endphp

@section('content')
    <div class="col-sm-12">
        @if ($sideMenuBuilder->link_type == 'external')
            @if ($player == 'youtube')
                <iframe width="100%" height="700"
                    src="{{ isset($menu_link['href'][0]) && isset($menu_link['type'][0]) ? $menu_link['href'][0] . $menu_link['type'][0] : $sideMenuBuilder->link }}"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            @elseif ($player == 'vimeo')
                <iframe width="100%" height="700"
                    src="{{ isset($menu_link['href'][0]) && isset($menu_link['type'][0]) ? $menu_link['href'][0] . $menu_link['type'][0] : $sideMenuBuilder->link }}"
                    frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            @else
                <iframe id="moduleFrame" width="100%" height="700"
                    src="{{ isset($menu_link['href'][0]) && isset($menu_link['type'][0]) ? $menu_link['href'][0] . $menu_link['type'][0] : $sideMenuBuilder->link }}"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            @endif
        @else
            @if ($player == 'youtube')
                <iframe width="100%" height="700"
                    src="{{ isset($menu_link['href'][0]) && isset($menu_link['type'][0]) ? $menu_link['href'][0] . $menu_link['type'][0] : $sideMenuBuilder->link }}"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            @elseif ($player == 'vimeo')
                <iframe width="100%" height="700"
                    src="{{ isset($menu_link['href'][0]) && isset($menu_link['type'][0]) ? $menu_link['href'][0] . $menu_link['type'][0] : $sideMenuBuilder->link }}"
                    frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            @else
                <iframe id="moduleFrame" width="100%" height="700"
                    src="{{ env('APP_URL') . $sideMenuBuilder->link }}"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            @endif
        @endif
    </div>
@endsection
