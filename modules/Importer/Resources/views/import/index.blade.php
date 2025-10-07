@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('importer::importer.import_products'))

    <li class="active">{{ trans('importer::importer.import_products') }}</li>
@endcomponent

@section('content')
    <div class="row">
        <div class="btn-group pull-right">
            <a href="{{ asset('/samples/import/bulk_import_products_sample.zip') }}" class="btn btn-primary btn-actions">
                {{ trans('importer::importer.download_sample_file') }}
            </a>
        </div>
    </div>

    <div class="box m-b-0">
        <div class="box-body">
            @if ($exceptions !== null && count($exceptions)>0)
                <div class="alert alert-danger">
                    <ul class="errors">
                        @foreach ($exceptions as $exception)
                            <li>{!! $exception !!}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M5.00082 14.9995L14.9999 5.00041" stroke="#555555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.9999 14.9996L5.00082 5.00049" stroke="#555555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            @endif
        

            <form action="{{route('admin.importer.import')}}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="products" class="col-md-3 control-label text-left">
                                {{ trans('importer::importer.product_data_csv_or_excel') }}<span class="m-l-5 text-red">*</span>
                            </label>

                            <div class="col-md-7">
                                <input type="file" id="products" name="products" accept=".csv, .xls, .xlsx" class="form-control">
                            
                                @if ($errors->has('products'))
                                    <span class="help-block text-red">
                                        {{ $errors->first('products') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images" class="col-md-3 control-label text-left">
                                {{ trans('importer::importer.product_images_zip') }}
                            </label>

                            <div class="col-md-7">
                                <input type="file" id="images" name="images" accept=".zip" class="form-control">
                            
                                @if ($errors->has('images'))
                                    <span class="help-block text-red">
                                        {{ $errors->first('images') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="col-md-7 col-md-offset-3">
                                <button class="btn btn-primary" data-loading type="submit">
                                    {{ trans('importer::importer.import') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
