<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Business details") }}</h6>
    <div class="card-body">


        <div class="form-group">
            <label>{{ __("Name") }} <span class="required">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>{{ __("Description") }} <span class="required">*</span> <span id="word-count">0/1000 words</span>
             <br>
             <span style="color: green; font-size: 14px;">In a few words describe your business</span>
            </label>
            <div id="description" style="height: 200px">
                {!!  $directory->description !!}
            </div>
            {!! Form::hidden('description', null, ['class' => 'form-control']) !!}

        <br>

    </div>

    <div class="form-group">
        <label>{{ __("Category") }}</label>
        {!! Form::select('directory_category_id', $categories, null, ['class' => 'autocomplete form-controls ']) !!}
    </div>

</div>
</div>
<!-- Company logo new -->
<div class="card mb-4">
                <h6 class="card-header bg-white">Company logo (500 X 500) <span class="required">*</span></h6>
                <div class="card-body">
                <script type="text/template" id="qq-template-logo">
                    <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                        </div>
                        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                            <span class="qq-upload-drop-area-text-selector"></span>
                        </div>
                        <div class="qq-upload-button-selector qq-upload-button">
                            <div class="small">{{ __("Upload logo") }}</div>
                        </div>
                        <span class="qq-drop-processing-selector qq-drop-processing">
                        <span>{{ __("Processing dropped files...") }}</span>
                         <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                        </span>
                        <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                            <li>
                                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                </div>
                                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                <div class="qq-thumbnail-wrapper">
                                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                                </div>
                                <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                                <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                                    <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                                    {{ __("Retry") }}
                                </button>

                                <div class="qq-file-info">
                                    <div class="qq-file-name">
                                        <span class="qq-upload-file-selector qq-upload-file"></span>
                                        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                    </div>
                                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                    <span class="qq-upload-size-selector qq-upload-size"></span>
                                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                        <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                        <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                        <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                                    </button>
                                </div>
                            </li>
                        </ul>

                        <dialog class="qq-alert-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Close") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-confirm-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("No") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Yes") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-prompt-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <input type="text">
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Cancel") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Ok") }}</button>
                            </div>
                        </dialog>
                    </div>
                </script>

                <div id="fine-uploader-logo"></div>
                <div id="photo_inputs_logo"></div>
        </div>
        </div>
        <script>

                $('#fine-uploader-logo').fineUploader({
                    template: 'qq-template-logo',
                    request: {
                        endpoint: '{{route('create.uploadlogo', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        params: {
                          directory_id: {{ $directory->id}},
                        }
                    },
                    session: {
                        endpoint: '{{route('create.sessionlogo', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    thumbnails: {
                        placeholders: {
                            waitingPath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/waiting-generic.png',
                            notAvailablePath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/not_available-generic.png'
                        }
                    },
                    chunking: {
                        enabled: false
                    },
                    validation: {
                        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                        image: {minWidth: 500, minHeight: 500},
                        itemLimit: 1,
                    },
                    deleteFile: {
                        enabled: true,
                        forceConfirm: true,
                        endpoint: '{{route('create.delete-logo', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    callbacks: {
                        onDelete: function(id, name) {
                            $( "input[name='photos[" + id + "]']" ).remove();
                        },
                        onSessionRequestComplete: function(response, success) {
                            if (success) {
                                console.log(response)
                                for(var i = 0; i < response.length; i++) {
                                    $('#photo_inputs_logo').append("<input type='hidden' name='photos-logo[" + i + "]' value=' " + response[i].thumbnailUrl + " '/>");
                                }
                                //$('#photo_inputs_video').append("<input type='hidden' name='photos[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onComplete: function(id, name, response) {
                            if (response.success) {
                                $('#photo_inputs_logo').append("<input type='hidden' name='photos-logo[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onStatusChange: function(id, oldStatus, newStatus) {
                            if(oldStatus === null && newStatus == "upload successful") {
                                $('#photo_inputs_logo').append("<input type='hidden' name='photos-logo[" + id + "]' value=''/>");
                            }
                        }
                    }
                });

        </script>
<!-- end logo -->

<!-- banner -->
<div class="card mb-4">
                <h6 class="card-header bg-white">Banner Images (680 X 460)
                  <span class="required">
                    @if(!$directory->is_topbrand)
                    *
                    @endif
                  </span>
              </h6>
                <div class="card-body">
                <script type="text/template" id="qq-template-gallery">
                    <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                        </div>
                        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                            <span class="qq-upload-drop-area-text-selector"></span>
                        </div>
                        <div class="qq-upload-button-selector qq-upload-button">
                            <div class="small">{{ __("Upload") }}</div>
                        </div>
                        <span class="qq-drop-processing-selector qq-drop-processing">
<span>{{ __("Processing dropped files...") }}</span>
<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
</span>
                        <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                            <li>
                                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                </div>
                                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                <div class="qq-thumbnail-wrapper">
                                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                                </div>
                                <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                                <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                                    <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                                    {{ __("Retry") }}
                                </button>

                                <div class="qq-file-info">
                                    <div class="qq-file-name">
                                        <span class="qq-upload-file-selector qq-upload-file"></span>
                                        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                    </div>
                                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                    <span class="qq-upload-size-selector qq-upload-size"></span>
                                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                        <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                        <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                        <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                                    </button>
                                </div>
                            </li>
                        </ul>

                        <dialog class="qq-alert-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Close") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-confirm-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("No") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Yes") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-prompt-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <input type="text">
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Cancel") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Ok") }}</button>
                            </div>
                        </dialog>
                    </div>
                </script>

                <div id="fine-uploader-gallery"></div>
                <div id="photo_inputs"></div>
        </div>
        </div>



<script>

        $('#fine-uploader-gallery').fineUploader({
            template: 'qq-template-gallery',
            request: {
                endpoint: '{{route('create.upload', $directory)}}',
                customHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                params: {
                  directory_id: {{ $directory->id}},
                }
            },
            session: {
                endpoint: '{{route('create.session', $directory)}}',
                customHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            thumbnails: {
                placeholders: {
                    waitingPath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            chunking: {
                enabled: false
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                image: {minWidth: 680, minHeight: 460},
            },
            deleteFile: {
                enabled: true,
                forceConfirm: true,
                endpoint: '{{route('create.delete-image', $directory)}}',
                customHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            callbacks: {
                onDelete: function(id, name) {
                    $( "input[name='photos[" + id + "]']" ).remove();
                },
                onSessionRequestComplete: function(response, success) {
                    if (success) {
                        console.log(response)
                        for(var i = 0; i < response.length; i++) {
                            $('#photo_inputs').append("<input type='hidden' name='photos[" + i + "]' value=' " + response[i].thumbnailUrl + " '/>");
                        }
                        //$('#photo_inputs').append("<input type='hidden' name='photos[" + id + "]' value=' " + response.path + " '/>");
                    }
                },
                onComplete: function(id, name, response) {
                    if (response.success) {
                        $('#photo_inputs').append("<input type='hidden' name='photos[" + id + "]' value=' " + response.path + " '/>");
                    }
                },
                onStatusChange: function(id, oldStatus, newStatus) {
                    if(oldStatus === null && newStatus == "upload successful") {
                        $('#photo_inputs').append("<input type='hidden' name='photos[" + id + "]' value=''/>");
                    }
                }
            }
        });

</script>
<!-- end banner -->
@if($directory->is_topbrand)
<div class="card mb-4">
                <h6 class="card-header bg-white">Video (5mb) mp4 <span class="required"></span></h6>
                <div class="card-body">
                <script type="text/template" id="qq-template-video">
                    <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                        </div>
                        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                            <span class="qq-upload-drop-area-text-selector"></span>
                        </div>
                        <div class="qq-upload-button-selector qq-upload-button">
                            <div class="small">{{ __("Upload video") }}</div>
                        </div>
                        <span class="qq-drop-processing-selector qq-drop-processing">
<span>{{ __("Processing dropped files...") }}</span>
<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
</span>
                        <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                            <li>
                                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                </div>
                                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                <div class="qq-thumbnail-wrapper">
                                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                                </div>
                                <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                                <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                                    <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                                    {{ __("Retry") }}
                                </button>

                                <div class="qq-file-info">
                                    <div class="qq-file-name">
                                        <span class="qq-upload-file-selector qq-upload-file"></span>
                                        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                    </div>
                                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                    <span class="qq-upload-size-selector qq-upload-size"></span>
                                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                        <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                        <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                                    </button>
                                    <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                        <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                                    </button>
                                </div>
                            </li>
                        </ul>

                        <dialog class="qq-alert-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Close") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-confirm-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("No") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Yes") }}</button>
                            </div>
                        </dialog>

                        <dialog class="qq-prompt-dialog-selector">
                            <div class="qq-dialog-message-selector"></div>
                            <input type="text">
                            <div class="qq-dialog-buttons">
                                <button type="button" class="qq-cancel-button-selector">{{ __("Cancel") }}</button>
                                <button type="button" class="qq-ok-button-selector">{{ __("Ok") }}</button>
                            </div>
                        </dialog>
                    </div>
                </script>

                <div id="fine-uploader-video"></div>
                <div id="photo_inputs_video"></div>
        </div>
        </div>
        <div class="card mb-4">
                        <h6 class="card-header bg-white">Top Brand Page Background (1600 X 1024) <span class="required"></span></h6>
                        <div class="card-body">
                        <script type="text/template" id="qq-template-gallery-banner">
                            <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                                <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                                </div>
                                <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                                    <span class="qq-upload-drop-area-text-selector"></span>
                                </div>
                                <div class="qq-upload-button-selector qq-upload-button">
                                    <div class="small">{{ __("Upload") }}</div>
                                </div>
                                <span class="qq-drop-processing-selector qq-drop-processing">
        <span>{{ __("Processing dropped files...") }}</span>
        <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
        </span>
                                <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                                    <li>
                                        <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                        <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                        </div>
                                        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                        <div class="qq-thumbnail-wrapper">
                                            <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                                        </div>
                                        <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                                        <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                                            <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                                            {{ __("Retry") }}
                                        </button>

                                        <div class="qq-file-info">
                                            <div class="qq-file-name">
                                                <span class="qq-upload-file-selector qq-upload-file"></span>
                                                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                            </div>
                                            <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                            <span class="qq-upload-size-selector qq-upload-size"></span>
                                            <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                                <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                                            </button>
                                            <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                                <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                                            </button>
                                            <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                                <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                                            </button>
                                        </div>
                                    </li>
                                </ul>

                                <dialog class="qq-alert-dialog-selector">
                                    <div class="qq-dialog-message-selector"></div>
                                    <div class="qq-dialog-buttons">
                                        <button type="button" class="qq-cancel-button-selector">{{ __("Close") }}</button>
                                    </div>
                                </dialog>

                                <dialog class="qq-confirm-dialog-selector">
                                    <div class="qq-dialog-message-selector"></div>
                                    <div class="qq-dialog-buttons">
                                        <button type="button" class="qq-cancel-button-selector">{{ __("No") }}</button>
                                        <button type="button" class="qq-ok-button-selector">{{ __("Yes") }}</button>
                                    </div>
                                </dialog>

                                <dialog class="qq-prompt-dialog-selector">
                                    <div class="qq-dialog-message-selector"></div>
                                    <input type="text">
                                    <div class="qq-dialog-buttons">
                                        <button type="button" class="qq-cancel-button-selector">{{ __("Cancel") }}</button>
                                        <button type="button" class="qq-ok-button-selector">{{ __("Ok") }}</button>
                                    </div>
                                </dialog>
                            </div>
                        </script>

                        <div id="fine-uploader-gallerybanner"></div>
                        <div id="photo_inputs_banner"></div>
                </div>
                </div>

                <div class="card mb-4">
                                <h6 class="card-header bg-white">Galleries (500 X 500)</h6>
                                <div class="card-body">
                                <script type="text/template" id="qq-template-gallery-topbrand">
                                    <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
                                        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                                        </div>
                                        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                                            <span class="qq-upload-drop-area-text-selector"></span>
                                        </div>
                                        <div class="qq-upload-button-selector qq-upload-button">
                                            <div class="small">{{ __("Upload") }}</div>
                                        </div>
                                        <span class="qq-drop-processing-selector qq-drop-processing">
                <span>{{ __("Processing dropped files...") }}</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                </span>
                                        <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                                            <li>
                                                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                                <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                                </div>
                                                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                                <div class="qq-thumbnail-wrapper">
                                                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                                                </div>
                                                <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                                                <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                                                    <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                                                    {{ __("Retry") }}
                                                </button>

                                                <div class="qq-file-info">
                                                    <div class="qq-file-name">
                                                        <span class="qq-upload-file-selector qq-upload-file"></span>
                                                        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                                    </div>
                                                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                                    <span class="qq-upload-size-selector qq-upload-size"></span>
                                                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                                        <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                                                    </button>
                                                    <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                                        <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                                                    </button>
                                                    <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                                        <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>

                                        <dialog class="qq-alert-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <div class="qq-dialog-buttons">
                                                <button type="button" class="qq-cancel-button-selector">{{ __("Close") }}</button>
                                            </div>
                                        </dialog>

                                        <dialog class="qq-confirm-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <div class="qq-dialog-buttons">
                                                <button type="button" class="qq-cancel-button-selector">{{ __("No") }}</button>
                                                <button type="button" class="qq-ok-button-selector">{{ __("Yes") }}</button>
                                            </div>
                                        </dialog>

                                        <dialog class="qq-prompt-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <input type="text">
                                            <div class="qq-dialog-buttons">
                                                <button type="button" class="qq-cancel-button-selector">{{ __("Cancel") }}</button>
                                                <button type="button" class="qq-ok-button-selector">{{ __("Ok") }}</button>
                                            </div>
                                        </dialog>
                                    </div>
                                </script>

                                <div id="fine-uploader-gallery-topbrand"></div>
                                <div id="photo_inputs-topbrand"></div>
                        </div>
                        </div>

        <script>

                $('#fine-uploader-gallerybanner').fineUploader({
                    template: 'qq-template-gallery-banner',
                    request: {
                        endpoint: '{{route('create.uploadbanner', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        params: {
                          directory_id: {{ $directory->id}},
                        }
                    },
                    session: {
                        endpoint: '{{route('create.sessionbanner', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    thumbnails: {
                        placeholders: {
                            waitingPath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/waiting-generic.png',
                            notAvailablePath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/not_available-generic.png'
                        }
                    },
                    chunking: {
                        enabled: false
                    },
                    validation: {
                        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                        image: {minWidth: 1000},
                        itemLimit: 1,
                    },
                    deleteFile: {
                        enabled: true,
                        forceConfirm: true,
                        endpoint: '{{route('create.delete-bannerimage', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    callbacks: {
                        onDelete: function(id, name) {
                            $( "input[name='photos-banner[" + id + "]']" ).remove();
                        },
                        onSessionRequestComplete: function(response, success) {
                            if (success) {
                                console.log(response)
                                for(var i = 0; i < response.length; i++) {
                                    $('#photo_inputs_banner').append("<input type='hidden' name='photos-banner[" + i + "]' value=' " + response[i].thumbnailUrl + " '/>");
                                }
                                //$('#photo_inputs').append("<input type='hidden' name='photos[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onComplete: function(id, name, response) {
                            if (response.success) {
                                $('#photo_inputs_banner').append("<input type='hidden' name='photos-banner[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onStatusChange: function(id, oldStatus, newStatus) {
                            if(oldStatus === null && newStatus == "upload successful") {
                                $('#photo_inputs_banner').append("<input type='hidden' name='photos-banner[" + id + "]' value=''/>");
                            }
                        }
                    }
                });

        </script>
        <script>

           $('#fine-uploader-gallery-topbrand').fineUploader({
                  template: 'qq-template-gallery-topbrand',
                  request: {
                      endpoint: '{{route('create.uploadgallery', $directory)}}',
                      customHeaders: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      params: {
                        directory_id: {{ $directory->id}},
                      }
                  },
                  session: {
                      endpoint: '{{route('create.sessionbrand', $directory)}}',
                      customHeaders: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                  },
                  thumbnails: {
                      placeholders: {
                          waitingPath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/waiting-generic.png',
                          notAvailablePath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/not_available-generic.png'
                      }
                  },
                  chunking: {
                      enabled: false
                  },
                  validation: {
                      allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                      image: {minWidth: 500, minHeight: 500},
                  },
                  deleteFile: {
                      enabled: true,
                      forceConfirm: true,
                      endpoint: '{{route('create.delete-galimage', $directory)}}',
                      customHeaders: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                  },
                  callbacks: {
                      onDelete: function(id, name) {
                          $( "input[name='photos-brand[" + id + "]']" ).remove();
                      },
                      onSessionRequestComplete: function(response, success) {
                          if (success) {
                              console.log(response)
                              for(var i = 0; i < response.length; i++) {
                                  $('#photo_inputs-topbrand').append("<input type='hidden' name='photos-brand[" + i + "]' value=' " + response[i].thumbnailUrl + " '/>");
                              }
                              //$('#photo_inputs-topbrand').append("<input type='hidden' name='photos-brand[" + id + "]' value=' " + response.path + " '/>");
                          }
                      },
                      onComplete: function(id, name, response) {
                          if (response.success) {
                              $('#photo_inputs-topbrand').append("<input type='hidden' name='photos-brand[" + id + "]' value=' " + response.path + " '/>");
                          }
                      },
                      onStatusChange: function(id, oldStatus, newStatus) {
                          if(oldStatus === null && newStatus == "upload successful") {
                              $('#photo_inputs-topbrand').append("<input type='hidden' name='photos-brand[" + id + "]' value=''/>");
                          }
                      }
                  }
              });

        </script>


        <div class="card mb-4">
            <h6 class="card-header bg-white">{{ __("About us") }} <span class="required">*</span></h6>
            <div class="card-body">
            <div class="form-group">
                <!--<label>{{ __("About us") }}</label>-->
                <div id="about" style="height: 200px">
                    {!! $directory->about !!}
                </div>
                {!! Form::hidden('about', null, ['class' => 'form-control']) !!}
             </div>
            </div>
        </div>
        <div class="card mb-4">
            <h6 class="card-header bg-white">{{ __("Sevices") }} <span class="required">*</span></h6>
            <div class="card-body">
            <div class="form-group">
                <label></label>
                <div id="services" style="height: 200px">
                    {!!  $directory->services !!}
                </div>
                {!! Form::hidden('services', null, ['class' => 'form-control']) !!}
             </div>
            </div>
        </div>
        <div class="card mb-4">
                    <h6 class="card-header bg-white">{{ __("Teams") }}</h6>
                    <div class="card-body">
                       <a data-toggle="modal" data-target="#teamsModal" href="" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a><br/>
                      <table>
                       @foreach($directory->teams as $team)
                         <tr>
                          <td>
                           <img height="60" src="{{ $team->photo }}?timestamp="/>
                          </td>
                          <td>
                           <a class="team-edit" data-id="{{ $team->id }}" data-name="{{ $team->name }}" data-position="{{ $team->position }}">Edit</a><br/>
                           {{ $team->name }}<br/>
                           {{ $team->position }}
                           </td>
                         </tr>
                       @endforeach
                       </table>
                   </div>
            </div>
            <div data-focus="false" id="teamsModal" class="modal" tabindex="-1" role="dialog">
             <div class="modal-dialog" role="document">
              <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
               </div>
               <div class="modal-body">

                <div class="form-group">
                <label for="name">Name</label>
                 <input name="teamname" type="text" class="form-control" id="inputName" aria-describedby="nameHelp" placeholder="Enter name"/>
                 <input value="0" name="teamid" type="hidden" id="inputId">
               </div>
              <div class="form-group">
              <label for="inputPosition">Position</label>
              <input name="teamposition" type="text" class="form-control" id="inputPosition" placeholder="Position">
              </div>
             <div class="form-group">
             <label for="inputFile">Input File</label>
             <input name="teamimage" type="file" class="form-control-file" id="inputFile">
              </div>
                <button onclick="form_submit()" id="save-btn" type="submit" class="btn btn-primary">Save team</button>

               </div>
               <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
             </div>
             </div>
            </div>
          <script>
            if($('#about').length) {
                var quills = new Quill('#about', {
                    placeholder: '',
                    theme: 'snow'  // or 'bubble'
                });
                quills.on('editor-change', function (eventName, args) {
                    $('input[name=about]').val(quills.root.innerHTML);
                });
            }
            if($('#services').length) {
                var quills_ser = new Quill('#services', {
                    placeholder: '',
                    theme: 'snow'  // or 'bubble'
                });
                quills_ser.on('editor-change', function (eventName, args) {
                    $('input[name=services]').val(quills_ser.root.innerHTML);
                });
            }
            $(document).ready(function(){
            $('.team-edit').on('click', function(e){
             $('.modal-title').html('Edit team');
              var name = $(this).data('name');
              var position = $(this).data('position');
              var id = $(this).data('id');
              $("#inputId").val(id);
              $("#inputPosition").val(position);
              $("#inputName").val(name);
              $("#teamsModal").modal(
               {
                backdrop: false
               }
              );
            });
            $('#teamsModal').on('hidden.bs.modal', function (e) {
             // do something...
             $("#inputId").val(0);
            })
          });
        </script>

        <script>

                $('#fine-uploader-video').fineUploader({
                    template: 'qq-template-video',
                    request: {
                        endpoint: '{{route('create.uploadvideo', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        params: {
                          directory_id: {{ $directory->id}},
                        }
                    },
                    session: {
                        endpoint: '{{route('create.sessionvideo', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    thumbnails: {
                        placeholders: {
                            waitingPath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/waiting-generic.png',
                            notAvailablePath: 'https://cdn.jsdelivr.net/npm/fine-uploader@5.16.0/fine-uploader/placeholders/not_available-generic.png'
                        }
                    },
                    chunking: {
                        enabled: false
                    },
                    validation: {
                        allowedExtensions: ['mp4'],
                        sizeLimit: 5120000,
                        itemLimit: 1,
                    },
                    deleteFile: {
                        enabled: true,
                        forceConfirm: true,
                        endpoint: '{{route('create.delete-video', $directory)}}',
                        customHeaders: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    callbacks: {
                        onDelete: function(id, name) {
                            $( "input[name='photos[" + id + "]']" ).remove();
                        },
                        onSessionRequestComplete: function(response, success) {
                            if (success) {
                                console.log(response)
                                for(var i = 0; i < response.length; i++) {
                                    $('#photo_inputs_video').append("<input type='hidden' name='photos-video[" + i + "]' value=' " + response[i].thumbnailUrl + " '/>");
                                }
                                //$('#photo_inputs_video').append("<input type='hidden' name='photos[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onComplete: function(id, name, response) {
                            if (response.success) {
                                $('#photo_inputs_video').append("<input type='hidden' name='photos-video[" + id + "]' value=' " + response.path + " '/>");
                            }
                        },
                        onStatusChange: function(id, oldStatus, newStatus) {
                            if(oldStatus === null && newStatus == "upload successful") {
                                $('#photo_inputs_video').append("<input type='hidden' name='photos-video[" + id + "]' value=''/>");
                            }
                        }
                    }
                });

        </script>
  @endif
<div class="card mb-4">
    <h6 class="card-header bg-white">{{ __("Contact") }}</h6>
    <div class="card-body">
      <div class="form-group">
          <label>{{ __("Email") }}</label>
          {!! Form::email('email', null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
          <label>{{ __("Phone") }} <span class="required">*</span></label>
          {!! Form::text('phone', null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
          <label>{{ __("Website") }}</label>
          {!! Form::url('website', null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
          <label>{{ __("Address") }} <span class="required">*</span></label>
          {!! Form::text('address', null, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
            <label>{{ __("Location on map") }}</label>
            {!! Form::text('location', null, ['class' => 'form-control', 'id' => 'location']) !!}
            {!! Form::hidden('lat', null, ['class' => 'form-control', 'id' => 'lat']) !!}
            {!! Form::hidden('lng', null, ['class' => 'form-control', 'id' => 'lng']) !!}

      <div id="create_map" style="width: 100%; height: 400px;"></div>

			<div class="row mt-4">
				<div class="col-6">
					<div class="form-group">
						<label>{{ __("City") }} <span class="required">*</span></label>

            {!! Form::select('city', $city_array, $city, ['class' => 'autocomplete form-controls ']) !!}
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>{{ __("Country") }}</label>
						{!! Form::text('country', null, ['class' => 'form-control', 'id' => 'c', 'readonly' => 'readonly']) !!}
					</div>
				</div>
			</div>

        </div>

    </div>
</div>

<script>
    const limit = 1000;
    if($('#description').length) {
        var quill = new Quill('#description', {
            placeholder: '',
            theme: 'snow'  // or 'bubble'
        });
        $("#word-count").html(quill.getLength() + "/1000");
        quill.on('editor-change', function (eventName, ...args) {
          if (quill.getLength() <= limit) {
            $("#word-count").html(quill.getLength() + "/1000");
            $('input[name=description]').val(quill.root.innerHTML);
          }else{
            if (eventName === 'text-change') {
              // args[0] will be delta
              const { ops } = args[0];
              let updatedOps;
             if (ops.length === 1) {
               // text is inserted at the beginning
               updatedOps = [{ delete: ops[0].insert.length }];
             } else {
               console.log("meeeee", ops[1]);
               // ops[0] == {retain: numberOfCharsBeforeInsertedText}
               // ops[1] == {insert: "example"}
               updatedOps = [ops[0], { delete: ops[1].insert.length }];
             }
              quill.updateContents({ ops: updatedOps });
            } else if (eventName === 'selection-change') {
              // args[0] will be old range
            }
          }
        });
    }
    if($('#create_map').length) {

        $('#create_map').locationpicker({
            location: {
                latitude: {{ $directory->lat ? $directory->lat : 0.00}},
                longitude: {{ $directory->lng ? $directory->lng : 0.00}}
            },
            radius: 0,
            enableAutocomplete: true,
            enableAutocompleteBlur: true,
            autocompleteOptions: {
               componentRestrictions: {country: 'ng'}
            },
            inputBinding: {
                latitudeInput: $('#lat'),
                longitudeInput: $('#lng'),
                locationNameInput: $('#location')
            },

            onchanged: function (currentLocation, radius, isMarkerDropped) {
                var addressComponents = $(this).locationpicker('map').location.addressComponents;
                //$('#city').val(addressComponents.city);
                $('#country').val('Nigeria');
                //$('#country').val(addressComponents.country);
            }
        });

    }
</script>
