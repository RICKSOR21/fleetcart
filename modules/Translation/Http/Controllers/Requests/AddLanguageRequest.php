<?php

namespace Modules\Translation\Http\Controllers\Requests;

use Modules\Core\Http\Requests\Request;

class AddLanguageRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'translation::languages/attributes';


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'language' => 'required',
        ];
    }
}
