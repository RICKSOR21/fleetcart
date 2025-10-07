<?php

namespace Modules\Importer\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class ValidateOptionFormat implements Rule
{
    protected string $message = 'The :attribute has invalid format.';

    public function passes($attribute, $value)
    {
        $options = $this->normalizedOptions($value);

        foreach ($options as $index => $option) {
            // Check required fields
            if (!isset($option['name'], $option['type'], $option['is_required'])) {
                $this->message = "Option at index {$index} is missing required fields.";
                return false;
            }

            $type = $option['type'];
            $name = $option['name'];

            // Types that require 'values' array
            $typesWithValues = ['dropdown', 'checkbox', 'checkbox_custom', 'radio'];
            if (in_array($type, $typesWithValues)) {
                if (empty($option['values']) || !is_array($option['values'])) {
                    $this->message = "Option '{$name}' must have at least one value.";
                    return false;
                }

                foreach ($option['values'] as $vIndex => $val) {
                    if (!isset($val['label'], $val['price'], $val['price_type'])) {
                        $this->message = "Option '{$name}' has incomplete value at index {$vIndex}.";
                        return false;
                    }
                }
            }

            // Text input types
            if (in_array($type, ['field', 'textarea'])) {
                if (isset($option['max_characters']) && !is_numeric($option['max_characters'])) {
                    $this->message = "Option '{$name}' has an invalid max_characters value.";
                    return false;
                }

                if (isset($option['price']) && !is_numeric($option['price'])) {
                    $this->message = "Option '{$name}' has an invalid price.";
                    return false;
                }

                if (isset($option['price_type']) && !in_array($option['price_type'], ['fixed', 'percentage'])) {
                    $this->message = "Option '{$name}' has an invalid price_type.";
                    return false;
                }
            }

            // Date types (can add more validations if needed)
            if ($type === 'date') {
                if (isset($option['price']) && !is_numeric($option['price'])) {
                    $this->message = "Option '{$name}' has an invalid price.";
                    return false;
                }

                if (isset($option['price_type']) && !in_array($option['price_type'], ['fixed', 'percentage'])) {
                    $this->message = "Option '{$name}' has an invalid price_type.";
                    return false;
                }
            }
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }

    public function normalizedOptions(string $optionsString): Collection
    {
        $optionStrings = explode('||', $optionsString);
        $optionDataList = collect();

        foreach ($optionStrings as $optionString) {
            $parts = explode(';', $optionString);
            $optionData = [];
            $values = [];

            foreach ($parts as $part) {
                if (preg_match('/^values\[(\d+)\]\[(.+)\]=(.+)$/', $part, $matches)) {
                    $index = $matches[1];
                    $key = $matches[2];
                    $value = $matches[3];
                    $values[$index][$key] = $value;
                } elseif (strpos($part, '=') !== false) {
                    [$key, $value] = explode('=', $part, 2);
                    $optionData[$key] = $value;
                }
            }

            if (!empty($values)) {
                $optionData['values'] = array_values($values);
            }

            $optionDataList->push($optionData);
        }

        return $optionDataList;
    }
}
