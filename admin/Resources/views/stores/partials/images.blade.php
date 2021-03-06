<div class="card mb-4">
                <h6 class="card-header bg-white">Banner Images (851 X 460) <span class="required">*</span></h6>
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
                endpoint: '{{route('createstore.upload', $store)}}',
                customHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                params: {
                  store_id: {{ $store->id}},
                }
            },
            session: {
                endpoint: '{{route('createstore.session', $store)}}',
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
                image: {minWidth: 851},
            },
            deleteFile: {
                enabled: true,
                forceConfirm: true,
                endpoint: '{{route('createstore.delete-image', $store)}}',
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
