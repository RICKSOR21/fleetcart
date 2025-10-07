<?php

namespace Modules\Importer\Http\Requests;

use Modules\Core\Http\Requests\Request;

class ImportRequest extends Request
{
    public function rules(): array
    {
        return [
            'products' => 'required|mimes:xlsx,xls,csv|max:9999',
            'images' => 'nullable|file|mimes:zip|max:99999',
        ];
    }
}
