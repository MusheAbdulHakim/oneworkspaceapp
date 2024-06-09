@php
    $color = !empty(company_setting('color'))?company_setting('color'):'theme-1';
@endphp
@extends('layouts.main')
@section('page-title')
    {{__('Messenger')}}
@endsection
@section('page-breadcrumb')
    {{ __('Messenger') }}
@endsection
@push('css')
@if ($color == 'theme-1')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #0CAF60 3.46%, #0CAF60 99.86%), #0CAF60 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #0CAF60 3.46%, #0CAF60 99.86%), #0CAF60 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #0CAF60 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #0CAF60 3.46%, #0CAF60 99.86%), #0CAF60 !important;
        }

        .m-header svg {
            color: #0CAF60 !important;
        }

        .active-tab {
            border-bottom: 2px solid #0CAF60 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #0CAF60 3.46%, #0CAF60 99.86%), #0CAF60 !important;
        }

        .lastMessageIndicator {
            color: #0CAF60 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #0CAF60 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #0CAF60 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-2')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #75C251 3.46%, #75C251 99.86%), #75C251 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #75C251 3.46%, #75C251 99.86%), #75C251 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #75C251 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #75C251 3.46%, #75C251 99.86%), #75C251 !important;
        }

        .m-header svg {
            color: #75C251 !important;
        }

        .active-tab {
            border-bottom: 2px solid #75C251 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #75C251 3.46%, #75C251 99.86%), #75C251 !important;
        }

        .lastMessageIndicator {
            color: #75C251 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #75C251 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #75C251 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif
@if ($color == 'theme-3')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #584ED2 3.46%, #584ED2 99.86%), #584ED2 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #584ED2 3.46%, #584ED2 99.86%), #584ED2 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #584ED2 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #584ED2 3.46%, #584ED2 99.86%), #584ED2 !important;
        }

        .m-header svg {
            color: #584ED2 !important;
        }

        .active-tab {
            border-bottom: 2px solid #584ED2 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #584ED2 3.46%, #584ED2 99.86%), #584ED2 !important;
        }

        .lastMessageIndicator {
            color: #584ED2 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #584ED2 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #584ED2 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-4')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #145388 3.46%, #145388 99.86%), #145388 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #145388 3.46%, #145388 99.86%), #145388 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #145388 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #145388 3.46%, #145388 99.86%), #145388 !important;
        }

        .m-header svg {
            color: #145388 !important;
        }

        .active-tab {
            border-bottom: 2px solid #145388 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #145388 3.46%, #145388 99.86%), #145388 !important;
        }

        .lastMessageIndicator {
            color: #145388 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #145388 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #145388 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-5')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #B9406B 3.46%, #B9406B 99.86%), #B9406B !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #B9406B 3.46%, #B9406B 99.86%), #B9406B !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #B9406B !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #B9406B 3.46%, #B9406B 99.86%), #B9406B !important;
        }

        .m-header svg {
            color: #B9406B !important;
        }

        .active-tab {
            border-bottom: 2px solid #B9406B !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #B9406B 3.46%, #B9406B 99.86%), #B9406B !important;
        }

        .lastMessageIndicator {
            color: #B9406B !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #B9406B !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #B9406B !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-6')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #008ECC 3.46%, #008ECC 99.86%), #008ECC !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #008ECC 3.46%, #008ECC 99.86%), #008ECC !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #008ECC !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #008ECC 3.46%, #008ECC 99.86%), #008ECC !important;
        }

        .m-header svg {
            color: #008ECC !important;
        }

        .active-tab {
            border-bottom: 2px solid #008ECC !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #008ECC 3.46%, #008ECC 99.86%), #008ECC !important;
        }

        .lastMessageIndicator {
            color: #008ECC !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #008ECC !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #008ECC !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-7')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #922C88 3.46%, #922C88 99.86%), #922C88 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #922C88 3.46%, #922C88 99.86%), #922C88 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #922C88 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #922C88 3.46%, #922C88 99.86%), #922C88 !important;
        }

        .m-header svg {
            color: #922C88 !important;
        }

        .active-tab {
            border-bottom: 2px solid #922C88 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #922C88 3.46%, #922C88 99.86%), #922C88 !important;
        }

        .lastMessageIndicator {
            color: #922C88 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #922C88 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #922C88 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-8')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #C0A145 3.46%, #C0A145 99.86%), #C0A145 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #C0A145 3.46%, #C0A145 99.86%), #C0A145 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #C0A145 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #C0A145 3.46%, #C0A145 99.86%), #C0A145 !important;
        }

        .m-header svg {
            color: #C0A145 !important;
        }

        .active-tab {
            border-bottom: 2px solid #C0A145 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #C0A145 3.46%, #C0A145 99.86%), #C0A145 !important;
        }

        .lastMessageIndicator {
            color: #C0A145 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #C0A145 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #C0A145 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-9')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #48494B 3.46%, #48494B 99.86%), #48494B !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #48494B 3.46%, #48494B 99.86%), #48494B !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #48494B !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #48494B 3.46%, #48494B 99.86%), #48494B !important;
        }

        .m-header svg {
            color: #48494B !important;
        }

        .active-tab {
            border-bottom: 2px solid #48494B !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #48494B 3.46%, #48494B 99.86%), #48494B !important;
        }

        .lastMessageIndicator {
            color: #48494B !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #48494B !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #48494B !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif

@if ($color == 'theme-10')
    <style type="text/css">
        .m-list-active,
        .m-list-active:hover,
        .m-list-active:focus {
            background: linear-gradient(141.55deg, #0C7785 3.46%, #0C7785 99.86%), #0C7785 !important;
        }

        .mc-sender p {
            background: linear-gradient(141.55deg, #0C7785 3.46%, #0C7785 99.86%), #0C7785 !important;
        }

        .messenger-favorites div.avatar {
            box-shadow: 0px 0px 0px 2px #0C7785 !important;
        }

        .messenger-listView-tabs a,
        .messenger-listView-tabs a:hover,
        .messenger-listView-tabs a:focus {
            color: linear-gradient(141.55deg, #0C7785 3.46%, #0C7785 99.86%), #0C7785 !important;
        }

        .m-header svg {
            color: #0C7785 !important;
        }

        .active-tab {
            border-bottom: 2px solid #0C7785 !important;
        }

        .messenger-infoView nav a {

            color: linear-gradient(141.55deg, #0C7785 3.46%, #0C7785 99.86%), #0C7785 !important;
        }

        .lastMessageIndicator {
            color: #0C7785 !important;
        }

        .messenger-list-item td span .lastMessageIndicator {

            color: #0C7785 !important;
            font-weight: bold;
        }

        .messenger-sendCard button svg {
            color: #0C7785 !important;
        }

        .messenger-list-item.m-list-active td span .lastMessageIndicator {
            color: #fff !important;
        }
    </style>
@endif
    @include('Chatify::layouts.headLinks')
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="messenger">
                        {{-- ----------------------Users/Groups lists side---------------------- --}}
                        <div class="messenger-listView">
                            {{-- Header and search bar --}}
                            <div class="m-header">
                                <nav>
                                    <nav class="m-header-right">
                                        <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                                    </nav>
                                </nav>
                                {{-- Search input --}}
                                <input type="text" class="messenger-search" placeholder="Search" />
                                {{-- Tabs --}}
                                <div class="messenger-listView-tabs">
                                    <a href="#" @if($route == 'user') class="active-tab" @endif data-view="users">
                                                                               <span class="ti ti-clock chatify-icon" title="{{__('Recent')}}"></span>
                                    </a>
                                    <a href="#" @if($route == 'group') class="active-tab" @endif data-view="groups">
                                                                               <span class="ti ti-users" title="{{__('Members')}}"></span>
                                    </a>
                                </div>
                            </div>
                            {{-- tabs and lists --}}
                            <div class="m-body">
                                {{-- Lists [Users/Group] --}}
                                {{-- ---------------- [ User Tab ] ---------------- --}}
                                <div class="@if($route == 'user') show @endif messenger-tab app-scroll" data-view="users">

                                    {{-- Favorites --}}
                                    <div class="favorites-section">
                                        <p class="messenger-title">{{__('Favorites')}}</p>
                                        <div class="messenger-favorites app-scroll-thin"></div>
                                    </div>

                                    {{-- Saved Messages --}}
                                    {!! view('Chatify::layouts.listItem', ['get' => 'saved','id' => $id])->render() !!}

                                    {{-- Contact --}}
                                    <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>


                                </div>

                                {{-- ---------------- [ Group Tab ] ---------------- --}}

                                <div class="all_members @if($route == 'group') show @endif messenger-tab app-scroll" data-view="groups">
                                    {{-- items --}}
                                    <p style="text-align: center;color:grey;">{{__('Soon will be available')}}</p>
                                </div>
                                {{-- ---------------- [ Search Tab ] ---------------- --}}
                                <div class=" messenger-tab app-scroll" data-view="search">
                                    {{-- items --}}
                                    <p class="messenger-title">Search</p>
                                    <div class="search-records">
                                        <p class="message-hint center-el"><span>Type to search..</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ----------------------Messaging side---------------------- --}}
                        <div class="messenger-messagingView">
                            {{-- header title [conversation name] amd buttons --}}
                            <div class="m-header m-header-messaging">
                                <nav>
                                    {{-- header back button, avatar and user name --}}
                                    <div style="display: inline-flex;">
                                        <a href="#" class="show-listView"><i class="ti ti-arrow-left"></i></a>
                                        @if(!empty(Auth::user()->avatar))
                                            <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px; background-image: url('{{ get_file(Auth::user()->avatar) }}');">
                                            </div>
                                        @else
                                            <div class="avatar av-s header-avatar" style=" margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;background-image: url('{{ get_file('uploads/users-avatar/avatar.png') }}');"></div>
                                        @endif
                                        <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                                    </div>
                                    {{-- header buttons --}}
                                    <nav class="m-header-right">
                                        <a href="#" id="image" class="add-to-favorite  "><i id="foo" class="fa fa-star"></i></a>
                                        <a href="#" class="show-infoSide header-icon">
                                            <svg class="svg-inline--fa fa-info-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>
                                        </a>
                                    </nav>
                                    <script src="{{ asset('js/jquery.min.js') }}"></script>
                                    <script>
                                        $('#image').click(function() {
                                            $('#foo').css({
                                                'color': '#FFC107'
                                            });
                                        });
                                    </script>
                                </nav>
                            </div>
                            {{-- Internet connection --}}
                            <div class="internet-connection">
                                <span class="ic-connected">{{__('Connected')}}</span>
                                <span class="ic-connecting">{{__('Connecting...')}}</span>
                                <span class="ic-noInternet">{{__('Please add pusher settings for using messenger.')}}</span>
                            </div>
                            {{-- Messaging area --}}
                            <div class="m-body app-scroll">
                                <div class="messages">
                                    <p class="message-hint" style="margin-top: calc(30% - 126.2px);"><span>{{__('Please select a chat to start messaging')}}</span></p>
                                </div>
                                {{-- Typing indicator --}}
                                <div class="typing-indicator">
                                    <div class="message-card typing">
                                        <p>
                                <span class="typing-dots">
                                    <span class="dot dot-1"></span>
                                    <span class="dot dot-2"></span>
                                    <span class="dot dot-3"></span>
                                </span>
                                        </p>
                                    </div>
                                </div>
                                {{-- Send Message Form --}}
                                @include('Chatify::layouts.sendForm')
                            </div>
                        </div>
                        {{-- ---------------------- Info side ---------------------- --}}
                        <div class="messenger-infoView app-scroll text-center">
                            {{-- nav actions --}}
                            <nav>
                                <a href="#"><i class="fas fa-times"></i></a>
                            </nav>
                            {!! view('Chatify::layouts.info')->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Chatify::layouts.modals')
@endsection
@push('scripts')
    @include('Chatify::layouts.footerLinks')
@endpush
