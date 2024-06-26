@props(['bookmarks' => $bookmarks])
@foreach ($bookmarks as $i => $bookmark)
    <div class="col-md-3 col-sm-12 col-12 product-card">
        @if ($bookmark->type == '1')
            <a href="{{ route('bookmark.iframe', ['urlBookmark' => \Crypt::encrypt($bookmark)]) }}">
            @else
                <a href="{{ $bookmark->url }}" target="_blank">
        @endif
        <div class="card manager-card rounded-0"
            @if (!empty($bookmark->color) && $bookmark->color != '#fff' && $bookmark->color != '#ffffff') style="background-color: {{ $bookmark->color }}; color: #fff;" @endif>
            <div class="product-img justify-content-center my-3">
                <span class="text-center text-white">{{ $bookmark->title }}</span>
            </div>
        </div>
        </a>
    </div>
@endforeach
