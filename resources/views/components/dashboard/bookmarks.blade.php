@props(['bookmarks' => $bookmarks])
@foreach ($bookmarks as $i => $bookmark)
    <div class="col-md-3 col-sm-12 col-12 product-card">
        <div class="card manager-card rounded-0"
            @if (!empty($bookmark->color) && $bookmark->color != '#fff' && $bookmark->color != '#ffffff') style="background-color: {{ $bookmark->color }}; color: #fff;" @endif>
            <div class="checkbox-custom">
                <div class="btn-group card-option float-end">
                    <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical text-white"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" data-title="{{ __('Edit Bookmark') }}"
                            href="{{ route('bookmark.edit', $bookmark->id) }}"
                            data-toggle="tooltip">
                            <span><i class="ti ti-edit"></i> Edit</span>
                        </a>
                        {{ Form::open(['route' => ['bookmark.destroy', $bookmark->id], 'class' => 'm-0']) }}
                        @method('DELETE')
                        <a href="#!" class="dropdown-item bs-pass-para show_confirm" aria-label="Delete"
                            data-confirm="{{ __('Are You Sure?') }}"
                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="delete-form-{{ $bookmark->id }}">
                            <span><i class="ti ti-trash"></i> Delete</span>
                        </a>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            @if ($bookmark->type == '1')
            <a href="{{ route('bookmark.iframe', ['urlBookmark' => \Crypt::encrypt($bookmark)]) }}">
            @else
                <a href="{{ $bookmark->url }}" target="_blank">
            @endif

            <div class="product-img justify-content-center my-3">
                <span class="text-center text-white">{{ $bookmark->title }}</span>
            </div>
            </a>
        </div>
    </div>
@endforeach
