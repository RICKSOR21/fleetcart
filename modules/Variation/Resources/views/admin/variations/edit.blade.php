@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('admin::resource.edit', ['resource' => trans('variation::variations.variation')]))
    @slot('subtitle', $variation->name)

    <li><a href="{{ route('admin.variations.index') }}">{{ trans('variation::variations.variations') }}</a></li>
    <li class="active">{{ trans('admin::resource.edit', ['resource' => trans('variation::variations.variation')]) }}</li>
@endcomponent

@section('content')
    <div id="app" class="box"></div>
@endsection

@include('variation::admin.variations.partials.scripts')

@push('globals')
    <script type="module">
        FleetCart.data['variation'] = {!! $variation_resource !!};
    </script>

    @vite([
        'modules/Variation/Resources/assets/admin/sass/main.scss',
        'modules/Variation/Resources/assets/admin/js/edit.js',
        'modules/Media/Resources/assets/admin/sass/main.scss',
        'modules/Media/Resources/assets/admin/js/main.js',
    ])
@endpush
