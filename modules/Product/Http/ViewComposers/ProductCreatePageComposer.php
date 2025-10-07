<?php

namespace Modules\Product\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Tag\Entities\Tag;
use Modules\Brand\Entities\Brand;
use Modules\Tax\Entities\TaxClass;
use Modules\Option\Entities\Option;
use Modules\Category\Entities\Category;
use Modules\Variation\Entities\Variation;
use Modules\Attribute\Entities\AttributeSet;

class ProductCreatePageComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'permissions' => auth()->user()->permissions,
            'globalVariations' => Variation::globals()->latest()->get(),
            'globalOptions' => Option::globals()->latest()->get(),
            'brands' => Brand::keyValuedList(),
            'categories' => Category::keyValuedtreeList(),
            'taxClasses' => TaxClass::list(),
            'tags' => Tag::keyValuedList(),
            'attributeSets' => AttributeSet::with('attributes.values')->get()->sortBy('name'),
        ]);
    }
}
