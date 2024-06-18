@props(['bookmarks' => $bookmarks])
@foreach($bookmarks as $i => $bookmark)
    <div class="col-md-4 col-12 product-card">
        @if ($bookmark->type == '1')
        <a href="{{ route('bookmark.iframe',['urlBookmark' => \Crypt::encrypt($bookmark)]) }}">
        @else
        <a href="{{ $bookmark->url }}" target="_blank">
        @endif
            <div class="card" @if(!empty($bookmark->color) && ($bookmark->color != '#fff') && ($bookmark->color != '#ffffff')) style="background-color: {{ $bookmark->color }}; color: #fff;" @endif>
                <div class="card-body">
                    @if (!empty($bookmark->image))
                    <div class="theme-avtar">
                        <img src="{{ get_file(('uploads/bookmarks/'.$bookmark->image)) }}"
                            alt="site image" style="max-width: 100%;">
                    </div>
                    @endif
                    <h6 class="card-header">{{ $bookmark->title }}</h6>
                    <span>{{ $bookmark->priority }}</span>
                    <span>{{ $bookmark->url }}</span>
                    <p>{{ $bookmark->description }}</p>
                </div>
            </div>
        </a>
    </div>
@endforeach
