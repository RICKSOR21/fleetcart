<?php

namespace Modules\Importer\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Attribute\Entities\Attribute;

class ValidAttributeFormat implements Rule
{
    protected $invalidBlock = null;
    protected $errorMessage = null;

    public function passes($attribute, $value)
    {
        $attributes = $this->parseAttributes($value);
        $rules = [
            '*.attribute_set' => 'required|string',
            '*.name' => 'required|string',
            '*.categories' => 'required|array',
            '*.slug' => 'required|string',
            '*.filterable' => 'required|in:true,false,1,0',
            '*.values' => 'required|array'
        ];

        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {

            $messages = [];

            foreach ($validator->errors()->messages() as $field => $errors) {
                foreach ($errors as $error) {
                    $field = ucfirst($field);
                    $messages[] = "{$field}: {$error}<br />";
                }
            }

            $errorMessage = implode('. ', $messages) . '.';
            $this->errorMessage =  $errorMessage;
            return false;
        }


        return true;
    }

    public function message()
    {
        return $this->errorMessage;


    }

    private function parseAttributes($string)
    {
        // Split all attributes using '||'
        $attributeBlocks = array_map('trim', explode('||', $string));
        $parsed = [];

        foreach ($attributeBlocks as $block) {
            // Match [Attribute Set]
            preg_match('/\[(.*?)\]/', $block, $setMatch);
            $attributeSet = $setMatch[1] ?? null;

            // Remove the [attribute_set] part from the block
            $block = preg_replace('/\[(.*?)\]\s*/', '', $block);

            // Split other fields by '|'
            $parts = array_map('trim', explode('|', $block));

            $data = [
                'attribute_set' => $attributeSet,
            ];

            foreach ($parts as $part) {
                if (str_contains($part, 'Categories:')) {
                    $data['categories'] = array_map('trim', explode(',', str_replace('Categories:', '', $part)));
                } elseif (str_contains($part, 'Slug:')) {
                    $data['slug'] = trim(str_replace('Slug:', '', $part));
                } elseif (str_contains($part, 'Filterable:')) {
                    $data['filterable'] = trim(str_replace('Filterable:', '', $part));
                } elseif (str_contains($part, 'Values:')) {
                    $data['values'] = array_map('trim', explode(',', str_replace('Values:', '', $part)));
                } else {
                    // Assume it's the name
                    $data['name'] = trim($part);
                }
            }

            $parsed[] = $data;
        }

        return $parsed;
    }



}
