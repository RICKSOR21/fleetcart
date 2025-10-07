<?php

namespace Modules\Translation\Http\Controllers\Requests;

use Modules\Core\Http\Requests\Request;

class ImportTranslationRequest extends Request
{

    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'translation::attributes';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:json',
        ];
    }
}
