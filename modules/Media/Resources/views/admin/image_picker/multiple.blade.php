<div class="multiple-images-wrapper">
    <h4>{{ $title }}</h4>

    <button type="button" class="image-picker btn btn-default" data-input-name="{{ $inputName }}" data-multiple>
        <i class="fa fa-folder-open m-r-5"></i>{{ trans('media::media.browse') }}
    </button>

    <div class="multiple-images">
        <div class="col-md-12">
            <div class="row">
                <div class="image-list image-holder-wrapper clearfix">
                    @if ($files->isEmpty())
                        <div class="image-holder placeholder cursor-auto">
                            <i class="fa fa-picture-o"></i>
                        </div>
                    @else
                        @foreach ($files as $file)
                            <div class="image-holder">
                                <img src="{{ $file->path }}">
                                <button type="button" class="btn remove-image" data-input-name="{{ $inputName }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="{{ $inputName }}" value="{{ $file->id }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
