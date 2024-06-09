{{ Form::open(array('route' => 'footer_section_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Section Heading', __('Section Heading'), ['class' => 'form-label']) }}
                    {{ Form::text('footer_section_heading',null, ['class' => 'form-control', 'placeholder' => __('Enter Section Heading'),'required'=>'required']) }}
                </div>
            </div>

            <div class="border" >
                <div class="row py-3 border-bottom"> 
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
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                            {{ Form::text('footer_section_text[1][title]',null, ['class' => 'form-control', 'placeholder' => __('Enter Section title'),'required'=>'required']) }}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{ Form::label('Title', __('Title Link'), ['class' => 'form-label']) }}
                            {{ Form::text('footer_section_text[1][link]',null, ['class' => 'form-control', 'placeholder' => __('Title Link'),'required'=>'required']) }}
                        </div>
                    </div>
                    <div class="col-md-2 d-flex text-center align-items-center">
                        <a href="#" id="delete-card1" class="card-delete btn btn-danger btn-sm bs-pass-para" title="{{__('Delete')}}" data-title="{{__('Delete')}}" data-original-title="{{__('Delete')}}">
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