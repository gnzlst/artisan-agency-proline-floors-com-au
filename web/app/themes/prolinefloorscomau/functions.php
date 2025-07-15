<?php

use Roots\Acorn\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });

function proline_enqueue_lightbox_assets()
{
    wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css');
    wp_enqueue_script('glightbox-js', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], null, true);

    wp_add_inline_script('glightbox-js', "
        document.addEventListener('DOMContentLoaded', function () {
            const lightbox = GLightbox({
                selector: '.glightbox'
            });
        });
    ");
}
add_action('wp_enqueue_scripts', 'proline_enqueue_lightbox_assets');

function mytheme_wrap_images_with_lightbox($content)
{
    if (!is_singular() || !in_the_loop() || !is_main_query() || trim($content) === '') {
        return $content;
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

    $xpath = new DOMXPath($dom);

    $containers = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " lightbox-enabled ")]');

    foreach ($containers as $container) {
        $imgs = $container->getElementsByTagName('img');

        foreach ($imgs as $img) {
            $src = $img->getAttribute('src');
            if (!$src) continue;

            $parent = $img->parentNode;

            if ($parent->nodeName === 'a') continue;

            $a = $dom->createElement('a');
            $a->setAttribute('href', $src);
            $a->setAttribute('class', 'glightbox');
            $a->setAttribute('data-gallery', 'post-gallery');

            $parent->replaceChild($a, $img);
            $a->appendChild($img);
        }
    }

    $body = $dom->getElementsByTagName('body')->item(0);
    $newContent = '';
    foreach ($body->childNodes as $child) {
        $newContent .= $dom->saveHTML($child);
    }

    return $newContent;
}
add_filter('the_content', 'mytheme_wrap_images_with_lightbox');

add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if ($product && $product->get_type() === 'simple' && !$product->is_purchasable()) {
        return __('Learn More', 'woocommerce');
    }
    if ($product && $product->get_type() === 'variable' && !$product->is_purchasable()) {
        return __('Learn More', 'woocommerce');
    }
    if ($product && $product->get_type() === 'external') {
        return __('Learn More', 'woocommerce');
    }
    return $text;
}, 10, 2);

add_filter('woocommerce_loop_add_to_cart_link', function ($html, $product) {
    $excluded_pages = ['maintenance-accessories', 'hybrid-flooring-accessories'];
    foreach ($excluded_pages as $excluded) {
        if ((is_string($excluded) && is_page($excluded)) || (is_int($excluded) && is_page($excluded))) {
            return $html;
        }
    }
    if (has_term('Hybrid flooring', 'product_cat', $product->get_id())) {
        $custom_classes = 'proline-jungle-button-woocommerce-button bg-proline-gray text-proline-dark px-4 py-2 hover:bg-white';
        $order_class = 'proline-jungle-text';
    } else {
        $custom_classes = 'proline-persimmon-button-woocommerce-button bg-proline-gray text-proline-dark px-4 py-2 hover:bg-white';
        $order_class = 'proline-persimmon-text';
    }
    $html = preg_replace(
        '/(class="[^"]*)"/',
        '$1 ' . $custom_classes . '"',
        $html
    );
    $custom_text = '<div class="mb-4 text-sm font-semibold"><a href="/contact-us/" class="' . $order_class . '">Order a free measure and quote</a></div>';
    return $custom_text . $html;
}, 10, 2);

add_action('wp_enqueue_scripts', function () {
    $manifest_path = get_theme_file_path('public/build/manifest.json');
    if (!file_exists($manifest_path)) {
        return;
    }
    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = $manifest['resources/js/woo-blocks.js'] ?? null;
    if (!$entry || !isset($entry['file'])) {
        return;
    }
    $src = get_theme_file_uri('public/build/' . $entry['file']);
    wp_enqueue_script(
        'proline-woo-blocks',
        $src,
        [],
        null,
        true
    );
    if (function_exists('wp_create_nonce')) {
        wp_add_inline_script(
            'proline-woo-blocks',
            'window.wc_store_api_nonce = "' . wp_create_nonce('wc_store_api') . '";',
            'before'
        );
    }
});

add_action('rest_api_init', function () {
    register_rest_route('proline/v1', '/product-categories/(?P<id>\\d+)', [
        'methods' => 'GET',
        'callback' => function ($request) {
            $product_id = (int) $request['id'];
            $terms = get_the_terms($product_id, 'product_cat');
            if (is_wp_error($terms) || empty($terms)) {
                return [];
            }
            return array_map(function ($term) {
                return [
                    'name' => $term->name,
                    'slug' => $term->slug,
                    'link' => get_term_link($term),
                ];
            }, $terms);
        },
        'permission_callback' => '__return_true',
    ]);
});

add_filter('woocommerce_store_api_disable_nonce_check', '__return_true');

add_filter('woocommerce_is_purchasable', function ($purchasable, $product) {
    if ($product && $product->is_type('simple')) {
        $price = $product->get_price();
        if ($price === '' || $price === null || $price == 0) {
            return true;
        }
    }
    return $purchasable;
}, 10, 2);

add_filter('woocommerce_single_product_image_gallery_classes', function ($classes) {
    $classes[] = 'lightbox-enabled';
    return $classes;
});

add_filter('woocommerce_single_product_image_html', function ($html, $post_thumbnail_id) {
    $html = preg_replace(
        '/<a /',
        '<a class="glightbox" data-gallery="post-gallery" ',
        $html,
        1
    );
    return $html;
}, 10, 2);

add_filter('woocommerce_single_product_image_thumbnail_html', function ($html, $post_thumbnail_id) {
    $html = preg_replace(
        '/<a /',
        '<a class="glightbox" data-gallery="post-gallery" ',
        $html,
        1
    );
    return $html;
}, 10, 2);

add_action('wp_enqueue_scripts', function () {
    wp_add_inline_script('jquery', <<<JS
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('img[sizes^="auto,"]').forEach(img => {
    img.setAttribute('sizes', img.getAttribute('sizes').replace(/^auto,\\s*/, ''));
  });
});
JS);
});
