@extends('admin::layout')

@section('title', trans('translation::translations.translations'))

@component('admin::components.page.header')
    @slot('title', trans('translation::translations.translations'))

    <li class="active">{{ trans('translation::translations.translations') }}</li>
@endcomponent

@section('content')
    <div class="row">
        <div class="btn-group pull-right">
            <a href="{{ route('admin.language.translations.export', ['locale' => $language]) }}" class="btn btn-primary btn-actions">
                {{ trans('translation::translations.buttons.export') }}
            </a>
        </div>
    </div>

    <div class="box box-primary translations">
        <div class="box-body index-table">
            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('admin.language.translations.import', ['locale' => $language]) }}"
                class="mb-10 overflow-hidden"
            >
                @csrf

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="form-elements">
                            <div class="form-group">
                                <input type="file" class="form-control" id="file" name="file" accept=".json">
        
                                @if ($errors->has('file'))
                                    <span class="help-block text-red">
                                        {{ $errors->first('file') }}
                                    </span>
                                @endif
                            </div>
        
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" data-loading>
                                    {{ trans('translation::translations.buttons.import') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover translations-table">
                    <thead>
                    <tr>
                        <th>{{ trans('translation::translations.table.key') }}</th>

                        @foreach ($locales as $locale => $language)
                            <th>{{ $language['name'] }}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($keys as $key)
                        <tr>
                            <td>{{ $key }}</td>

                            @foreach ($locales as $locale => $language)
                                <td class="translation-td">
                                    <a
                                        href="#"
                                        class="translation editable-click {{ array_has($translations[$key], $locale) ? '' : 'editable-empty' }}"
                                        data-locale="{{ $locale }}"
                                        data-key="{{ $key }}"
                                    >
                                        {{ array_get($translations[$key], $locale) }}
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Translation/Resources/assets/admin/translations/sass/main.scss',
        'modules/Translation/Resources/assets/admin/translations/js/main.js',
    ])
@endpush
