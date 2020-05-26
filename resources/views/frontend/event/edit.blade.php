@extends('layouts.frontend')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('scripts')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBk-L7v6RJ1QVUtF48zHH8_eY7VWUvtluQ&callback=initMap">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.3/moment.min.js"></script>

    <script src="/js/micromodal.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>


        $('#description').summernote({
            placeholder: 'Введите описание',
            tabsize: 2,
            height: 300,
            maxWidth: 543,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

    </script>


    <script>

        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.image-upload-wrap').hide();

                    $('.file-upload-image').attr('src', e.target.result);
                    $('.file-upload-content').show();

                    $('.image-title').html(input.files[0].name);
                };

                reader.readAsDataURL(input.files[0]);
                console.log(input.files[0]);

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
            $('.image-upload-wrap').removeClass('image-dropping');
        });


        function initMap() {
            // The location of Uluru
                    @if($event->location[0])

            var lat_p = '{{ $event->location[0] }}';
            var lng_p = '{{ $event->location[1] }}';

            var LatLng = {lat: parseFloat(lat_p), lng: parseFloat(lng_p)};

            $('#event-location').attr('value', '('+ lat_p +',' + lng_p + ')')


            var map = new google.maps.Map(document.getElementById('map'), {zoom: 15, center: LatLng});

            var marker = new google.maps.Marker({
                position: LatLng,
                map: map
            });
                    @else
            var map = new google.maps.Map(document.getElementById('map'), {zoom: 13, center: {lat: 53.890763, lng: 27.565134}});
            var marker;
            @endif

            google.maps.event.addListener(map, 'click', function(event) {

                placeMarker(event.latLng);

            });

            function placeMarker(location) {

                if (marker == null)
                {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                    document.getElementById('event-location').setAttribute('value', location);
                }
                else
                {
                    marker.setPosition(location);
                    document.getElementById('event-location').setAttribute('value', location);
                }
            }
        }



        function showModal(name) {

            var modals = {

                success: {
                    title: "@lang('content.event.update.modal.success.title')",
                    content: "@lang('content.event.update.modal.success.content')",
                    buttons: {
                        confirm: "@lang('content.event.update.modal.success.confirm')",
                    },
                    action: function () {
                        location.href = "/organisation#events";
                    }
                },

                error: {
                    title: "@lang('content.event.update.modal.error.title')",
                    content: "@lang('content.event.update.modal.error.content')",
                    buttons: {
                        confirm: "@lang('content.event.update.modal.error.confirm')",
                    }
                },

                fail: {
                    title: "@lang('content.event.update.modal.fail.title')",
                    content: "@lang('content.event.update.modal.fail.content')",
                    buttons: {
                        confirm: "@lang('content.event.update.modal.fail.confirm')",
                    }
                }
            };

            $(".modal").attr("id", "modal_" + name);
            $("#modal-title").empty().append(modals[name].title);
            $("#modal-content").empty().append("<p>" + modals[name].content + "</p>");
            $("#modal-confirm").empty().append(modals[name].buttons.confirm);
            $("#modal-confirm").unbind('click');

            $("#modal-confirm").click( function (e) {
                MicroModal.close("modal_" + name)
                modals[name].action();
            });

            MicroModal.show("modal_" + name)
        }
        $(document).ready(function () {

            try {
                MicroModal.init();
            }catch (e) {
                console.log(e)
            }
            var image = '{{ $event->image }}';
            if(image !== '') {
                $('.image-upload-wrap').hide();

                $('.file-upload-image').attr('src', image);
                $('.file-upload-content').show();

                $('.image-title').html('{{ basename($event->image) }}');
            }







            function clearErrors() {
                try {
                    $(".operation-result").removeClass('show');
                    $(".operation-result").empty();

                    $(".is-invalid").removeClass('is-invalid');
                    $('.message-invalid').remove();

                }catch (e) {
                    console.log(e)
                }
            }

            var momentFormat = 'DD/MM/YYYY';
            var dateInputs = document.getElementsByClassName("datePicker");
            for (var i = 0; i < dateInputs.length; i++) {
                new IMask(dateInputs[i], {

                    mask: Date,
                    pattern: momentFormat,
                    lazy: false,
                    min: new Date(1970, 0, 1),
                    max: new Date(2030, 0, 1),

                    format: function (date) {
                        return moment(date).format(momentFormat);
                    },
                    parse: function (str) {
                        return moment(str, momentFormat);
                    },

                    blocks: {
                        YYYY: {
                            mask: IMask.MaskedRange,
                            from: 1970,
                            to: 2030
                        },
                        MM: {
                            mask: IMask.MaskedRange,
                            from: 1,
                            to: 12
                        },
                        DD: {
                            mask: IMask.MaskedRange,
                            from: 1,
                            to: 31
                        }
                    }
                });
            }


            var timeInputs = document.getElementsByClassName("timePicker");
            for (var i = 0; i < timeInputs.length; i++) {
                new IMask(timeInputs[i], {
                    overwrite: true,
                    autofix: true,
                    mask: 'HH:MM',
                    pattern: 'HH:`MM',
                    blocks: {
                        HH: {
                            mask: IMask.MaskedRange,
                            placeholderChar: 'HH',
                            from: 0,
                            to: 23,
                            maxLength: 2
                        },
                        MM: {
                            mask: IMask.MaskedRange,
                            placeholderChar: 'MM',
                            from: 0,
                            to: 59,
                            maxLength: 2
                        }
                    }
                });
            }







            try {

                $('#form').on('submit', function(ev){
                    ev.preventDefault();
                    callAjax();
                });

            }catch(e) {
                console.log(e)
            }
        });

        function callAjax() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            try {

                $("#submit").toggleClass('loading');
                var form = $('#form')[0];
                console.log(form);
                console.log((new FormData(form)));
                $.ajax(
                    {
                        url: '{{ route('organisation.events.update', $event->id) }}',
                        type: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,

                    })
                    .done(
                        function(data){
                            console.log(data)

                            $("#submit").toggleClass('loading');

                            for (var prop in data) {
                                $(".operation-result").append(prop.va);
                            }

                            for (let [key, value] of Object.entries(data)) {

                                if(key == 'message') {
                                    showModal('success');
                                }
                                else {
                                    showModal('error');
                                    $("#" + key).addClass('is-invalid').after(
                                        "<span class=\"message-invalid\" role=\"alert\"><strong>" + value + "</strong></span>" );
                                }
                            }
                        })
                    .fail(
                        function(jqXHR, ajaxOptions, thrownError) {

                            showModal('fail');
                            $("#submit").toggleClass('loading');

                        });

            }catch(e) {
                console.log(e);
            }





        }


        /*$(function () {
            $('#datetimepicker2').datetimepicker({
                locale: 'ru',
                minDate: moment(),
                format: "DD.MM.YYYY",
                allowInputToggle: true
            });

            $('#datetimepicker3').datetimepicker({
                format: 'HH.mm',
                allowInputToggle: true
            });
        });
*/
    </script>
@endsection


@section('content')

    <section class="event_section">
        <div class="content-wrapper">


            <form id="form" method="PATCH" class="event_form"  action="{{ route('organisation.events.update', $event->id) }}">
                @csrf
                @method('PATCH')

                <h3 class="event_form-title">
                    <strong>@lang('content.event.update.title')</strong>
                </h3>

                <div class="event_form-group">
                    <label for="title" class="event_form-group-label">@lang('content.event.create.name')</label>
                    <input id="title" required type="text" class="event_form-group-input @error('title') is-invalid @enderror" name="title" placeholder="@lang('content.event.create.name')" minlength="3" value="{{ $event->title }}" autofocus>

                    @error('title')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="event_form-group">
                    <label for="city" class="event_form-group-label">@lang('content.event.create.city')</label>
                    <select id="city" class="custom-select event_form-group-select @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required autofocus>
                        <option selected value>@lang('content.event.create.city')</option>
                        @foreach($cities as $city)
                            <option {{ ($city->id == $event->city_id)? 'selected': '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>

                    @error('city')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="event_form-group">
                    <label for="age" class="event_form-group-label">@lang('content.event.create.age')</label>
                    <select id="age" class="custom-select event_form-group-select @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" required autofocus>
                        <option selected value>@lang('content.event.create.age')</option>
                        @foreach($ages as $age)
                            <option {{ ($age->id == $event->age)? 'selected': '' }} value="{{ $age->id }}">{{ $age->name }}</option>
                        @endforeach
                    </select>

                    @error('age')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="event_form-group date-group">
                    <div class="event_form-date-group">
                        <label class="event_form-group-label" for="date_start">@lang('content.event.create.dateStart')</label>
                        <input type="text" required class="event_form-group-input datePicker" id="date_start" name="date_start" value="{{ $event->date_start->format('d/m/Y') }}"/>
                    </div>
                    <div class="event_form-date-group">
                        <label for="date_start" class="event_form-group-label">@lang('content.event.create.timeStart')</label>
                        <input required type="text" class="event_form-group-input timePicker" id="time_start" name="time_start" value="{{ $event->date_start->format('H:i') }}"/>
                    </div>
                </div>

                {{--<div class="event_form-group">
                    <label class="event_form-group-label" for="date_finish">@lang('content.event.create.dateFinish')</label>
                    <div class="event_form-date-group">
                        <input type="text" required class="event_form-group-input datePicker" id="date_finish" name="date_finish" value="{{ $event->date_finish->format('d/m/Y') }}"/>
                        <label for="date_finish" class="event_form-group-label">@lang('content.event.create.timeFinish')</label>
                        <input required type="text" class="event_form-group-input timePicker" id="time_finish" name="time_finish" value="{{ $event->date_finish->format('H:i') }}"/>
                    </div>
                </div>--}}

                <div class="event_form-group">
                    <label for="address" class="event_form-group-label">@lang('content.event.create.address')</label>
                    <input id="address" required type="text" class="event_form-group-input @error('address') is-invalid @enderror" name="address" placeholder="@lang('content.event.create.address')" minlength="3" value="{{ $event->address }}" autofocus>

                    @error('address')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="event_form-group">
                    <label for="type" class="event_form-group-label">@lang('content.event.create.type')</label>
                    <select id="type" class="custom-select event_form-group-select @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" required autofocus>
                        <option selected value>@lang('content.event.create.type')</option>
                        @foreach($types as $type)
                            <option {{ ($type->id == $event->type_id)? 'selected': '' }} value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>

                    @error('type')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="event_form-group description">

                    <textarea id="description" name="description" required minlength="20" type="text" class="event_form-group-input textarea @error('description') is-invalid @enderror"  name="description" placeholder="@lang('content.event.create.description')">{{ $event->description }}</textarea>

                    @error('description')
                    <span class="message-invalid" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>



                <div class="event_form-group">

                    <div class="file-upload">
                        <button class="button-secondary" type="button" onclick="$('.file-upload-input').trigger( 'click' )"><span>@lang('content.event.create.addImage')</span></button>

                        <div class="image-upload-wrap">
                            <input class="file-upload-input" type='file' name="image" onchange="readURL(this);" accept="image/*" />
                            <div class="drag-text">
                                <h3>@lang('content.event.create.loadImage')</h3>
                            </div>
                        </div>
                        <div class="file-upload-content">
                            <img class="file-upload-image" src="" alt="your image" />
                            <div class="image-title-wrap">
                                <button type="button" onclick="removeUpload()" class="button-secondary"><span>@lang('content.event.create.removeImage') &nbsp;</span> <span class="image-title">@lang('content.event.create.uploadImage')</span></button>
                            </div>
                        </div>
                    </div>
                </div>


                {{--<label for="event-image">@lang('content.event.create.loadPreview')</label>
                <div class="custom-file">
                    <label class="custom-file-label" for="event-image">png, jpg, jpeg</label>
                    <input type="file" class="custom-file-input" value="{{ old('image') }}" name="image" id="event-image" required accept="image/gif, image/jpeg, image/jpg, image/png">
                    <img id="uploadedimage"/>
                    <p>
                        <span id="imageerror" style="font-weight: bold; color: red"></span>
                    </p>
                </div>--}}


                <div class="event_form-group event_form-map-group">
                    <p class="map-title">@lang('content.event.create.map')</p>
                    <div class="map" id="map">
                    </div>
                    <input type="hidden" name="location" id="event-location">
                </div>

                <div class="event_form-group">
                    <button id="submit" class="button-secondary" role="button" type="submit">
                        <span>
                            @lang('content.event.update.save')
                        </span>
                        <div class="loading-icon"></div>
                    </button>
                </div>
                <div class="content-loader"></div>
                <p class="operation-result">
                </p>

                <p class="tip">@lang('content.event.update.notification')</p>

            </form>
        </div>
    </section>


    <div class="modal micromodal-slide" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <header class="modal__header">
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-content">
                </main>
                <footer class="modal__footer">
                    <button class="modal__btn modal__btn-primary" id="modal-confirm" data-micromodal-close aria-label="Close this dialog window"></button>
                </footer>
            </div>
        </div>
    </div>




@endsection


