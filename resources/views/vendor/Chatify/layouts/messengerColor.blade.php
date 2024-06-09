<style>
/* NProgress background */
#nprogress .bar{
    background: {{ $messengerColor }} ;
}
#nprogress .peg {
    box-shadow: 0 0 10px {{ $messengerColor }}, 0 0 5px {{ $messengerColor }} ;
}
#nprogress .spinner-icon {
  border-top-color: {{ $messengerColor }} ;
  border-left-color: {{ $messengerColor }} ;
}

.m-header svg{
    color: {{ $messengerColor }};
}




.messenger-list-item td b{
    background: {{ $messengerColor }};
}

.messenger-infoView nav a{
    color: {{ $messengerColor }};
}

.messenger-infoView-btns a.default{
    color: {{ $messengerColor }};
}

.mc-sender p{
  background: {{ $messengerColor }};
}

.messenger-sendCard button svg{
    color: {{ $messengerColor }};
}

.messenger-listView-tabs a,
.messenger-listView-tabs a:hover,
.messenger-listView-tabs a:focus{
    color: {{ $messengerColor }};
}

.active-tab{
    border-bottom: 2px solid {{ $messengerColor }};
}

.lastMessageIndicator{
    color: {{ $messengerColor }} ;
}

.messenger-favorites div.avatar{
    box-shadow: 0px 0px 0px 2px {{ $messengerColor }};
}

.dark-mode-switch{
    color: {{ $messengerColor }};
}
.m-list-active b{
    background: #fff !important;
    color: {{ $messengerColor }} !important;
}



</style>
