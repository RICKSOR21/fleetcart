<style>
    :root {
        --base-font-family: "{{ setting('storefront_display_font', 'Poppins') }}", sans-serif;
        --color-primary: {{ tinycolor($themeColor->toString())->toHexString() }};
        --color-primary-darken-10: {{ generate_color_shade($themeColor->toString(), 0.1) }};
        --color-primary-alpha-10: {{ tinycolor($themeColor->toString())->setAlpha(0.1)->toString() }};
        --color-primary-alpha-90: {{ tinycolor($themeColor->toString())->setAlpha(0.9)->toString() }};
    }
</style>
