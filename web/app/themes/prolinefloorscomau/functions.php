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
    if (!is_singular() || !in_the_loop() || !is_main_query()) {
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

add_filter('woocommerce_product_add_to_cart_text', function($text, $product) {
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

add_filter('woocommerce_loop_add_to_cart_link', function($html, $product) {
    $custom_classes = 'bg-proline-gray text-proline-dark px-4 py-2 hover:bg-white';
    $html = preg_replace(
        '/(class="[^"]*)"/',
        '$1 ' . $custom_classes . '"',
        $html
    );
    $custom_text = '<a href="#" class="mb-2 text-sm font-semibold">Order a free measure and quote</a>';
    return $custom_text . $html;
}, 10, 2);
