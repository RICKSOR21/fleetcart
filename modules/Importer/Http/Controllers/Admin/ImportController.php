<?php

namespace Modules\Importer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Importer\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportController extends Controller
{
    public function index()
    {
        $exceptions = [];

        if (session("exceptions")) {
            $exceptions = session("exceptions");

            session()->forget("exceptions");
        }

        return view("importer::import.index", compact("exceptions"));
    }

    public function store(Request $request)
    {
        session()->forget("exceptions");
        @set_time_limit(0);

        $request->validate([
            "products" => "required|mimes:xlsx,xls,csv|max:9999",
            "images" => "nullable|mimes:zip",
        ]);

        $app_path = app_path() . "\\";
        $app_path = str_replace("\\", "/", $app_path);

        try {
            ExcelFacade::import(
                new ProductsImport(),
                $request->file("products")
            );

            return back()->with(
                "success",
                trans("importer::importer.products_imported_successfully")
            );
        } catch (ValidationException $e) {
            $failures = $e->failures();

            return back()
                ->withErrors($failures)
                ->withInput();
        } catch (\Exception $e) {
            return back()->with(
                "error",
                sprintf(
                    "%s. %s.",
                    trans("importer::importer.something_went_wrong"),
                    $e->getMessage()
                )
            );
        }
    }
}
