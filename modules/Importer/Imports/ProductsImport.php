<?php

namespace Modules\Importer\Imports;

use Exception;
use finfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Modules\Attribute\Entities\Attribute as EntitiesAttribute;
use Modules\Attribute\Entities\AttributeSet;
use Modules\Attribute\Entities\AttributeValue;
use Modules\Attribute\Entities\ProductAttribute;
use Modules\Attribute\Entities\ProductAttributeValue;
use Modules\Brand\Entities\Brand;
use Modules\Category\Entities\Category;
use Modules\Importer\Rules\ValidateOptionFormat;
use Modules\Importer\Rules\ValidAttributeFormat;
use Modules\Media\Entities\File;
use Modules\Product\Entities\Product;
use Modules\Tag\Entities\Tag;

class ProductsImport implements OnEachRow, WithChunkReading, WithHeadingRow
{
    public function chunkSize(): int
    {
        return 200;
    }

    public function onRow(Row $row)
    {
        $row_data = $row->toArray();

        $validator = Validator::make($row_data, [
            "name" => "required|string|max:255",
            "description" => "required|string|max:65535",
            "short_description" => "nullable|string",
            "active" => "nullable|integer|in:1,0",
            "brand" => "nullable|string",
            "categories" => "nullable|string",
            "tags" => "nullable|string",
            "tax_class_id" => ["nullable", Rule::exists("tax_classes", "id")],
            "price" =>
            "required_without:variants|nullable|numeric|min:0|max:99999999999999",
            "special_price" => "nullable|numeric|min:0|max:99999999999999",
            "special_price_type" => [
                "nullable",
                Rule::in(["fixed", "percent"]),
            ],
            "special_price_start" => "nullable|date|before:special_price_end",
            "special_price_end" => "nullable|date|after:special_price_start",
            "manage_stock" => "nullable|boolean",
            "quantity" => "required_if:manage_stock,1|nullable|numeric",
            "in_stock" => "nullable|boolean",
            "new_from" => "nullable|date",
            "new_to" => "nullable|date",
            "additional_images" => "nullable|string",
            "base_image" => "nullable|string",
            "meta_title" => "nullable|string",
            "meta_description" => "nullable|string",
            "attributes" => ["nullable", new ValidAttributeFormat()],
            "options" => ["nullable", new ValidateOptionFormat()],
        ]);

        if ($validator->fails()) {
            $productSku = null;

            if (array_key_exists("sku", $row_data)) {
                $productSku = $row["sku"];
            }

            $messages = [];

            foreach ($validator->errors()->messages() as $field => $errors) {
                foreach ($errors as $error) {
                    $field = ucfirst($field);
                    $messages[] = "{$field}: {$error}<br />";
                }
            }

            $errorMessage = implode(". ", $messages) . ".";

            session()->push(
                "errors",
                sprintf(
                    "Product SKU: %s Errors: %s at Row Index: %s",
                    $productSku,
                    $errorMessage,
                    $row->getIndex()
                )
            );

            return;
        }
        try {
            $data = $this->normalize($row_data);
            $options = null;
            $attributes = null;

            if (array_key_exists("options", $data)) {
                $options = $data["options"];

                unset($data["options"]);
            }

            if (array_key_exists("attributes", $data)) {
                $attributes = $data["attributes"];

                unset($data["attributes"]);
            }

            $data["options"] = [];
            $data['brand_id'] = $data['brand'];

            request()->merge($data);

            $product = Product::create($data);

            if (!$product) {
                throw new \Exception(
                    sprintf(
                        "%s %s",
                        trans("importer::importer.write_to_database_failed"),
                        trans("importer::importer.product_not_created")
                    )
                );
            }

            if (request()->hasFile("product_images")) {
                $zipPath = request()
                    ->file("product_images")
                    ->getRealPath();

                $this->processImage(
                    $zipPath,
                    $data["files"]["base_image"],
                    Product::class,
                    $product->id,
                    "base_image"
                );
                $this->processImage(
                    $zipPath,
                    $data["files"]["additional_images"],
                    Product::class,
                    $product->id,
                    "additional_images"
                );

                $request = request();
                $cleaned = $request->all();

                unset($cleaned["files"]);

                $request->replace($cleaned);
            }
            if (!empty($options)) {
                $this->normalizedOptions($product, $options);
            }

            if (!empty($attributes)) {
                $this->processAttributes($attributes, $product);
            }
        } catch (Exception $e) {
            $productSku = null;

            if (array_key_exists("sku", $row_data)) {
                $productSku = $row["sku"];
            }

            session()->push(
                "errors",
                sprintf(
                    "Product SKU: %s Error: %s at Row: %s",
                    $productSku,
                    $e->getMessage(),
                    $row->getIndex()
                )
            );
        }
    }

    private function normalize(array $data)
    {
        return array_filter(
            [
                "name" => $data["name"],
                "sku" => $data["sku"],
                "description" => $data["description"],
                "short_description" => $data["short_description"],
                "is_active" => $data["active"] ?? 1,
                "brand" => empty($data["brand"])
                    ? null
                    : $this->getOrCreateBrandByName($data["brand"])->id,
                "categories" => $this->mapExploded(
                    $data["categories"],
                    function ($item) {
                        return $this->getOrCreateNestedCategory($item)->id;
                    }
                ),
                "tax_class_id" => $data["tax_class"],
                "tags" => $this->mapExploded(
                    $data["tags"],
                    function ($item) {
                        return $this->getOrCreateTagByName($item)->id;
                    },
                    ","
                ),
                "price" => $data["price"],
                "special_price" => $data["special_price"],
                "special_price_type" => $data["special_price_type"],
                "special_price_start" => $data["special_price_start"],
                "special_price_end" => $data["special_price_end"],
                "manage_stock" => isset($data["manage_stock"]) && $data["manage_stock"] ? 1 : 0,
                "qty" => isset($data["quantity"]) ? (int) $data["quantity"] : 0,
                "in_stock" => isset($data["in_stock"]) ? (int) $data["in_stock"] : 0,
                "new_from" => $data["new_from"],
                "new_to" => $data["new_to"],
                "files" => $this->normalizeFiles($data),
                "meta" => $this->normalizeMetaData($data),
                'is_virtual' => isset($data['is_virtual']) ? (int)$data['is_virtual'] : 0,
                "attributes" => $data["attributes"],
                "options" => $data["options"],
            ],
            function ($value) {
                return $value || is_numeric($value);
            }
        );
    }

    private function processAttributes($attributeString, $product)
    {
        $attributes = $this->parseAttributes($attributeString);
        $attributeIds = collect();
        foreach ($attributes as $attribute) {
            $attribute = (object) $attribute;
            $attributeSet = AttributeSet::whereHas("translations", function (
                $query
            ) use ($attribute) {
                $query->where("name", trim($attribute->attribute_set));
            })->first();

            if (!$attributeSet) {
                $attributeSet = AttributeSet::create([
                    "name" => trim($attribute->attribute_set),
                ]);
            }

            $entities = EntitiesAttribute::whereHas("translations", function (
                $query
            ) use ($attribute) {
                $query->where("name", $attribute->name);
            })->first();

            if (!$entities) {
                $entities = EntitiesAttribute::create([
                    "name" => $attribute->name,
                    "attribute_set_id" => $attributeSet->id,
                    "slug" => $attribute->slug,
                    "is_filterable" => $attribute->filterable ? 1 : 0,
                ]);
            }

            $entities->categories()->sync(
                $this->mapExploded(
                    implode(",", $attribute->categories),
                    function ($item) {
                        return $this->getOrCreateNestedCategory($item)->id;
                    }
                )
            );

            $productAttribute = ProductAttribute::create([
                "product_id" => $product->id,
                "attribute_id" => $entities->id,
            ]);

            $attributeValues = array_map(
                function ($value, $index) use ($entities, $productAttribute) {
                    $position = $index + 1;

                    // Try to find the attribute value by translation
                    $attributeValue = AttributeValue::where("attribute_id", $entities->id)
                        ->whereHas("translations", function ($query) use ($value) {
                            $query->where("value", $value);
                        })
                        ->first();

                    // Create it if it doesn't exist
                    if (!$attributeValue) {
                        $attributeValue = AttributeValue::create([
                            "value" => $value,
                            "attribute_id" => $entities->id,
                            "position" => $position,
                        ]);
                    }

                    return [
                        "product_attribute_id" => $productAttribute->id,
                        "attribute_value_id" => $attributeValue->id,
                    ];
                },
                $attribute->values,
                array_keys($attribute->values)
            );

            // Remove duplicate (product_attribute_id, attribute_value_id) pairs
            $attributeValues = collect($attributeValues)
                ->unique(function ($item) {
                    return $item['product_attribute_id'] . '-' . $item['attribute_value_id'];
                })
                ->values()
                ->all();

            ProductAttributeValue::insert($attributeValues);

            $attributeIds->push($entities->id);
        }
        return $attributeIds;
    }

    private function parseAttributes($string)
    {
        $attributeBlocks = array_map("trim", explode("||", $string));
        $parsed = [];

        foreach ($attributeBlocks as $block) {
            preg_match("/\[(.*?)\]/", $block, $setMatch);
            $attributeSet = $setMatch[1] ?? null;
            $block = preg_replace("/\[(.*?)\]\s*/", "", $block);
            $parts = array_map("trim", explode("|", $block));
            $data = [
                "attribute_set" => $attributeSet,
            ];

            foreach ($parts as $part) {
                if (str_contains($part, "Categories:")) {
                    $data["categories"] = array_map(
                        "trim",
                        explode(",", str_replace("Categories:", "", $part))
                    );
                } elseif (str_contains($part, "Slug:")) {
                    $data["slug"] = trim(str_replace("Slug:", "", $part));
                } elseif (str_contains($part, "Filterable:")) {
                    $data["filterable"] = trim(
                        str_replace("Filterable:", "", $part)
                    );
                } elseif (str_contains($part, "Values:")) {
                    $data["values"] = array_map(
                        "trim",
                        explode(",", str_replace("Values:", "", $part))
                    );
                } else {
                    $data["name"] = trim($part);
                }
            }

            $parsed[] = $data;
        }

        return $parsed;
    }

    private function explode($values)
    {
        if (trim($values) == "") {
            return false;
        }

        return array_map("trim", explode(",", $values));
    }

    public function mapExploded(
        $string = "",
        callable $callback,
        string $delimiter = ","
    ): array {
        if (empty($string)) {
            return [];
        }
        return collect(explode($delimiter, $string))
            ->map(fn($item) => $callback(trim($item)))
            ->toArray();
    }

    private function normalizeFiles(array $data)
    {
        return [
            "base_image" => !empty($data["base_image"])
                ? $this->explode($data["base_image"])
                : null,
            "additional_images" => !empty($data["additional_images"])
                ? $this->explode($data["additional_images"])
                : null,
        ];
    }

    private function normalizeMetaData($data)
    {
        return [
            "meta_title" => $data["meta_title"],
            "meta_description" => $data["meta_description"],
        ];
    }

    private function getOrCreateNestedCategory(string $categoryPath): ?Category
    {
        if (blank($categoryPath)) {
            return null;
        }

        $categoryNames = array_filter(
            array_map("trim", explode("///", $categoryPath))
        );
        $locale = app()->getLocale();

        $parentId = null;
        $category = null;

        foreach ($categoryNames as $name) {
            if (empty($name)) {
                continue;
            }

            $category = Category::where("parent_id", $parentId)
                ->whereHas("translations", function ($query) use (
                    $name,
                    $locale
                ) {
                    $query->where("name", $name)->where("locale", $locale);
                })
                ->first();

            if (!$category) {
                $category = Category::create([
                    "name" => $name,
                    "parent_id" => $parentId,
                    "slug" => Str::slug($name),
                    "is_searchable" => false,
                    "is_active" => true,
                ]);
            }

            $parentId = $category->id;
        }

        return $category;
    }

    private function getOrCreateBrandByName($brandName)
    {
        $brand = Brand::whereHas("translations", function ($query) use (
            $brandName
        ) {
            $query->where("name", $brandName);
        })->first();

        if (!$brand) {
            $brand = Brand::create([
                "name" => $brandName,
                "is_active" => 1,
            ]);
        }

        return $brand;
    }

    private function getOrCreateTagByName($tagName)
    {
        $tagName = trim($tagName);
        $tag = Tag::whereHas("translations", function ($query) use ($tagName) {
            $query->where("name", $tagName);
        })->first();

        if (!$tag) {
            $tag = Tag::create([
                "name" => $tagName,
            ]);
        }
        return $tag;
    }

    private function processImage(
        $zipPath,
        $imagePaths,
        $entityType,
        $entityId,
        $zone
    ) {
        if (empty($imagePaths)) {
            return;
        }

        $zipBaseUri = "zip://{$zipPath}#";
        $successPaths = collect();
        $failedPaths = collect();
        $file_ids = collect();

        foreach ($imagePaths as $imagePath) {
            $imageUri = "{$zipBaseUri}{$imagePath}";
            $content = file_get_contents($imageUri);
            $original_filename = basename($imagePath);
            $extesnion = pathinfo($original_filename, PATHINFO_EXTENSION);
            $filename = implode(".", [Str::random(40), $extesnion]);
            $path = Storage::put("media/" . $filename, $content);

            if ($path) {
                $size = strlen($content);
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($content);
                $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
                $file = File::create([
                    "user_id" => auth()->id(),
                    "disk" => config("filesystems.default"),
                    "filename" => substr($original_filename, 0, 255),
                    "path" => "media/{$filename}",
                    "extension" => $extension ?? "",
                    "mime" => $mimeType,
                    "size" => $size,
                ]);
                $file_ids->push($file->id);
                $successPaths->push($imagePaths);
            } else {
                $failedPaths->push($imagePaths);
            }

            $files = [
                $zone => $file_ids->toArray(),
            ];

            $this->syncFiles($files, $entityType, $entityId);
        }

        return [
            "success" => $successPaths,
            "failed" => $failedPaths,
        ];
    }

    public function syncFiles(array $files = [], $entityType, $entityId): void
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $zone => $fileIds) {
            $syncList = [];

            foreach (array_wrap($fileIds) as $fileId) {
                if (!empty($fileId)) {
                    $syncList[$fileId]["zone"] = $zone;
                    $syncList[$fileId]["entity_type"] = $entityType;
                }
            }

            $this->filterFiles($zone, $entityType, $entityId)->detach();
            $this->filterFiles($zone, $entityType, $entityId)->attach(
                $syncList
            );
        }
    }

    public function filterFiles(
        string|array $zones,
        string $entityType,
        int $entity_id
    ) {
        $entity = app($entityType)::find($entity_id);

        if (!$entity) {
            throw new \Exception(
                "Entity not found for type {$entityType} with ID {$entity_id}"
            );
        }

        return $entity->files()->wherePivotIn("zone", array_wrap($zones));
    }

    public function normalizedOptions($product, string $optionsString): void
    {
        if (!$product) {
            throw new \Exception("Product  not found.");
        }

        $optionStrings = explode("||", $optionsString);
        $optionPosition = 1;
        $optionIds = collect();

        foreach ($optionStrings as $optionString) {
            $parts = explode(";", $optionString);
            $optionData = [];
            $values = [];

            foreach ($parts as $part) {
                if (
                    preg_match(
                        '/^values\[(\d+)\]\[(.+)\]=(.+)$/',
                        $part,
                        $matches
                    )
                ) {
                    $index = $matches[1];
                    $key = $matches[2];
                    $value = $matches[3];
                    $values[$index][$key] = $value;
                } elseif (strpos($part, "=") !== false) {
                    [$key, $value] = explode("=", $part, 2);
                    $optionData[$key] = $value;
                }
            }

            $optionData["values"] = array_values($values);
            $option = $product->options()->create([
                "name" => $optionData["name"] ?? "Unnamed Option",
                "type" => $optionData["type"] ?? "dropdown",
                "is_required" => $optionData["is_required"] ?? 0,
                '$optionPosition' => $optionPosition,
                "is_global" => 1,
            ]);

            $optionIds->push($option->id);
            $optionValuePosition = 1;

            if (!empty($optionData["values"])) {
                foreach ($optionData["values"] as $valueData) {
                    $valueCreated = $option->values()->create([
                        "label" => $valueData["label"] ?? "Unnamed",
                        "price" => $valueData["price"] ?? 0,
                        "price_type" => $valueData["price_type"] ?? "fixed",
                        "position" => $optionValuePosition,
                        "option_id" => $option->id,
                    ]);

                    $optionValuePosition++;
                }
            } else {
                $valueCreated = $option->values()->create([
                    "label" => "Price",
                    "price" => 0,
                    "price_type" => "fixed",
                    "position" => 1,
                    "option_id" => $option->id,
                ]);
            }
        }

        $product->options()->sync($optionIds->toArray());
    }
}
