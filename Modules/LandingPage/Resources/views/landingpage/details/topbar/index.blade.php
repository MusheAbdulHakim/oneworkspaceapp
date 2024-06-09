@push('scripts')
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        document.getElementById('home_banner').onchange = function () {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        };

        $(document).ready(function() {
            $('#imageUploadForm').repeater({
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
            });
        });

        function updateImagePreview(inputElement) {
            var imageElement = inputElement.parentElement.parentElement.querySelector('img');
            if (inputElement.files.length > 0) {
                imageElement.src = window.URL.createObjectURL(inputElement.files[0]);
            } else {
                imageElement.src = '{{ get_file($settings['home_logo']) }}'; // Provide the path to your placeholder image.
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('delete-repeater-item')) {
                    event.preventDefault(); // Cancel the default action
                    var repeaterItem = event.target.closest('[data-repeater-item]');
                    if (repeaterItem) {
                        repeaterItem.remove();
                    }
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            const imageContainer = document.getElementById('imageContainer');
            const imageNamesInput = document.getElementById('imageNames');
            let deletedImageNames = [];

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const imageToDelete = button.getAttribute('data-image');
                    button.closest('.card').remove();
                    const currentImageNames = imageNamesInput.value.split(',');
                    const updatedImageNames = currentImageNames.filter(name => name !==
                        imageToDelete);
                    imageNamesInput.value = updatedImageNames.join(',');
                    deletedImageNames.push(imageToDelete);
                });
            });
        });
    </script>
@endpush


{{--  Start for all settings tab --}}
{{Form::model(null, array('route' => array('landingpage.store'), 'method' => 'POST')) }}
    @csrf
        <div class="border mb-5">
            <div class="form-group col-12 p-3">
                {{ Form::label('content', __('Message'), ['class' => 'col-form-label text-dark']) }}
                {{ Form::textarea('topbar_notification_msg',$settings['topbar_notification_msg'], ['class' => 'summernote form-control', 'required' => 'required', 'id'=>'topbar_notification']) }}
                <div class=" mt-3 text-end">
                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                </div>
            </div>
        </div>
{{ Form::close() }}
{{--  End for all settings tab --}}


@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush