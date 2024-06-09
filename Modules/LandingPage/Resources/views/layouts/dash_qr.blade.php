<div class="col-md-2 col-lg-2 col-12">
    <h4 class="h4 font-weight-400">{{__('Landingpage')}}</h4>
    <div class="card">
        <div class="card-body" style="min-height: 230px;">
            {{-- <h6 class="mb-2 text-center">{{ ('Landingpage') }}</h6> --}}
            <div class="mb-3 shareqrcode text-center"></div>
            <div class="d-flex justify-content-between">
                <a href="#!" class="btn btn-sm btn-light-primary w-100 cp_link"
                    data-link="{{ url('/') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="" data-bs-original-title="Click to copy site link">
                    {{ 'Site Link' }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-copy ms-1">
                        <rect x="9" y="9" width="13" height="13" rx="2"
                            ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                </a>
                <a href="#" id="socialShareButton"
                    class="socialShareButton btn btn-sm btn-primary ms-1 share-btn">
                    <i class="ti ti-share"></i>
                </a>
            </div>
            <div id="sharingButtonsContainer" class="sharingButtonsContainer"
                style="display: none;">
                <div class="Demo1 d-flex align-items-center justify-content-center hidden"></div>
            </div>
        </div>
    </div>
</div>