@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('admin::resource.create', ['resource' => trans('variation::variations.variation')]))

    <li><a href="{{ route('admin.variations.index') }}">{{ trans('variation::variations.variations') }}</a></li>
    <li class="active">{{ trans('admin::resource.create', ['resource' => trans('variation::variations.variation')]) }}</li>
@endcomponent

@section('content')
    <div id="app" class="box"></div>
@endsection

@include('variation::admin.variations.partials.scripts')

@push('globals')
    @vite([
        'modules/Variation/Resources/assets/admin/sass/main.scss',
        'modules/Variation/Resources/assets/admin/js/create.js',
        'modules/Media/Resources/assets/admin/sass/main.scss',
        'modules/Media/Resources/assets/admin/js/main.js',
    ])
@endpush
