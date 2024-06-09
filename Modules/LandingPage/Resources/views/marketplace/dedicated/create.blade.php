{{ Form::open(array('route' => array('dedicated_theme_section_store',$slug), 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                    {{ Form::text('dedicated_theme_section_heading',null, ['class' => 'form-control ', 'placeholder' => __('Enter Heading'),'required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('dedicated_theme_section_description', null, ['class' => 'summernote form-control', 'placeholder' => __('Enter Description'), 'id'=>'dedicated_theme_section_description','required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Section Image', __('Section Image'), ['class' => 'form-label']) }}
                    <input type="file" name="dedicated_theme_section_image" class="form-control" required>
                </div>
            </div>

            <div class="border" >
                <div class="row pt-3"> 
                    <div class="col"><h5>{{ __("Section Cards") }}</h5></div>
                    <div class="col-auto text-end">
                        <button id="add-cards-details"
                            class="btn btn-sm btn-primary btn-icon"
                             title="{{ __('Add More Cards') }}">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                </div>
                <div id="add-cards1" class="border-bottom row py-2">
                    <div class="col-md-10">
                        <div class="form-group">
                            {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                            {{ Form::text('dedicated_theme_section_cards[1][title]', null, ['class' => 'form-control', 'placeholder' => __('Enter Title')]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                            {{ Form::text('dedicated_theme_section_cards[1][description]',null, ['class' => 'form-control ', 'placeholder' => __('Enter Description')]) }}
                        </div>
                    </div>
                    <div class="col-md-2 d-flex text-center align-items-center">
                        <a href="#" id="delete-card1" class="card-delete btn btn-danger btn-sm align-items-center bs-pass-para" title="{{__('Delete')}}" data-title="{{__('Delete')}}" data-original-title="{{__('Delete')}}">
                            <i class="ti ti-trash text-white"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush
{{-- <script src="{{ asset('Modules/LandingPage/Resources/assets/js/repeater.js') }}"></script> --}}
<script>
    $("#add-cards-details").click(function(e){
        e.preventDefault()

    // get the last DIV which ID starts with ^= "another-participant"
    var $div = $('div[id^="add-cards"]:last');

    // Read the Number from that DIV's ID (i.e: 1 from "another-participant1")
    // And increment that number by 1
    var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;

    // Clone it and assign the new ID (i.e: from num 4 to ID "another-participant4")
    var $klon = $div.clone().prop('id', 'add-cards'+num );

    $klon.find('a').each(function() {
        this.id = "delete-card"+num;
    });

    // for each of the inputs inside the dive, clear it's value and 
    // increment the number in the 'name' attribute by 1
    $klon.find('input').each(function() {
    this.value= "";
    let name_number = this.name.match(/\d+/);
    name_number++;
    this.name = this.name.replace(/\[[0-9]\]+/, '['+name_number+']')
    });
    // Finally insert $klon after the last div
    $div.after( $klon );

    });
    
    $(document).on('click', '.card-delete', function(e) {
        e.preventDefault()

        var id = $(this).attr('id');
        var num = parseInt( id.match(/\d+/g), 10 );
        var card = document.getElementById("add-cards"+num);
        if(num != 1){
            card.remove();
        }
    });   
</script>