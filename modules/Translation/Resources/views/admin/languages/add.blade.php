@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('admin::resource.add', ['resource' => trans('translation::languages.language')]))

    <li><a href="{{ route('admin.languages.index') }}">{{ trans('translation::languages.languages') }}</a></li>
    <li class="active">{{ trans('admin::resource.add', ['resource' => trans('translation::languages.language')]) }}</li>
@endcomponent

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <form action="{{ route('admin.languages.store') }}" method="POST" class="form-horizontal">
                @csrf

                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="form-group">
                            <label for="language" class="col-md-3 control-label text-left">
                                {{ trans("translation::languages.language") }}<span class="m-l-5 text-red">*</span>
                            </label>

                            <div class="col-md-9">
                                <select name="language" id="language" class="form-control custom-select-black">
                                    <option value="">{{ trans('admin::admin.form.please_select') }}</option>

                                    @foreach ($locales as $locale => $language)
                                        <option value="{{ $locale }}">{{ $language }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('language'))
                                    <span class="help-block text-red">
                                        {{ $errors->first('language') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans("admin::admin.buttons.save") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
