@extends('layouts.app')

@section('content')
    @php
        $overview = get_post_meta(get_the_ID(), 'overview', true);
        $brochure = get_post_meta(get_the_ID(), 'brochure', true);
        $accesories = get_post_meta(get_the_ID(), 'accesories', true);
        $specifications = get_post_meta(get_the_ID(), 'specifications', true);
        $cleaning = get_post_meta(get_the_ID(), 'cleaning', true);
        $installation = get_post_meta(get_the_ID(), 'installation', true);
        $categories = get_the_terms(get_the_ID(), 'product_cat');
    @endphp
    <div class="container mx-auto py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2">
                @php
                    global $product;
                    $product = wc_get_product(get_the_ID());
                @endphp
                @php(do_action('woocommerce_before_single_product'))
                @php(do_action('woocommerce_before_single_product_summary'))
            </div>
            <div class="md:w-1/2">
                @if ($categories && !is_wp_error($categories))
                    <div class="text-sm text-gray-500">
                        {{ implode(', ', collect($categories)->pluck('name')->toArray()) }}
                    </div>
                @endif
                <h1 class="text-3xl font-bold mb-4 proline-persimmon-text mt-5">{{ the_title() }}</h1>
                <div class="prose mt-2 proline-persimmon-text">{!! wpautop($overview) !!}</div>
                <div class="mt-8 space-y-4">
                    @if ($accesories)
                        <details class="p-4 group">
                            <summary
                                class="text-xl font-semibold cursor-pointer select-none flex items-center justify-between">
                                Accessories
                                <span class="ml-2 transition-transform group-open:rotate-90">
                                    <svg class="w-4 h-4 text-proline-dark group-hover:text-proline-persimmon" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="prose mt-2">{!! wpautop($accesories) !!}</div>
                        </details>
                    @endif
                    @if ($specifications)
                        <details class="p-4 group">
                            <summary
                                class="text-xl font-semibold cursor-pointer select-none flex items-center justify-between">
                                Specifications
                                <span class="ml-2 transition-transform group-open:rotate-90">
                                    <svg class="w-4 h-4 text-proline-dark group-hover:text-proline-persimmon" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="prose mt-2">{!! wpautop($specifications) !!}</div>
                        </details>
                    @endif
                    @if ($cleaning)
                        <details class="p-4 group">
                            <summary
                                class="text-xl font-semibold cursor-pointer select-none flex items-center justify-between">
                                Cleaning
                                <span class="ml-2 transition-transform group-open:rotate-90">
                                    <svg class="w-4 h-4 text-proline-dark group-hover:text-proline-persimmon" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="prose mt-2">{!! wpautop($cleaning) !!}</div>
                        </details>
                    @endif
                    @if ($installation)
                        <details class="p-4 group">
                            <summary
                                class="text-xl font-semibold cursor-pointer select-none flex items-center justify-between">
                                Installation
                                <span class="ml-2 transition-transform group-open:rotate-90">
                                    <svg class="w-4 h-4 text-proline-dark group-hover:text-proline-persimmon" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="prose mt-2">{!! wpautop($installation) !!}</div>
                        </details>
                    @endif
                </div>
                <div class="mt-8">
                    <form class="flex flex-col items-start w-full" method="post" action="{{ esc_url(wc_get_cart_url()) }}">
                        <input type="hidden" name="add-to-cart" value="{{ get_the_ID() }}">
                        <button type="submit"
                            class="proline-persimmon-button-woocommerce-button bg-proline-gray text-proline-dark px-4 py-2 hover:bg-white w-full">
                            Add Free Samples To Cart
                        </button>
                    </form>
                    @if ($brochure)
                        <a href="{{ esc_url($brochure) }}" target="_blank" rel="noopener noreferrer"
                            class="proline-persimmon-button-woocommerce-brochure-button proline-light-gray-text px-4 py-2 tracking-wide w-full mt-4 text-center block">
                            Download Brochure
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-12">
            <?php
            if ($categories && !is_wp_error($categories)) {
                $category_ids = collect($categories)->pluck('term_id')->toArray();
                $args = [
                    'post_type' => 'product',
                    'posts_per_page' => 1000,
                    'post__not_in' => [get_the_ID()],
                    'tax_query' => [
                        [
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $category_ids,
                        ],
                    ],
                ];
                $related_products = collect(get_posts($args));
                // Get the parent category name for the current product
                $parent_cat = null;
                foreach ($categories as $cat) {
                    if ($cat->parent == 0) {
                        $parent_cat = $cat->name;
                        break;
                    }
                }
            }
            if (!isset($related_products)) {
                $related_products = collect();
            }
            ?>
            @if ($related_products->count())
                <div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
                <p class="has-text-align-center proline-persimmon-text">CHOOSE YOUR SAMPLES BY ADDING TO CART</p>
                <div style="height:16px" aria-hidden="true" class="wp-block-spacer"></div>
                <p class="has-text-align-center proline-section-large-text">Other
                    {{ $parent_cat ? $parent_cat : 'category' }} products</p>
                <div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
                    @foreach ($related_products as $related)
                        <div class="block overflow-hidden h-full flex flex-col">
                            <div class="relative">
                                <a href="{{ get_permalink($related->ID) }}">
                                    {!! get_the_post_thumbnail($related->ID, 'medium', ['class' => 'w-full h-48 object-cover']) !!}
                                </a>
                                <form method="post" action="{{ esc_url(wc_get_cart_url()) }}"
                                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 z-10 w-full">
                                    <input type="hidden" name="add-to-cart" value="{{ $related->ID }}">
                                    <button type="submit"
                                        class="proline-persimmon-button-woocommerce-add-sample-button proline-light-gray-text">Add
                                        Sample to Cart</button>
                                </form>
                            </div>
                            <div class="py-4 flex-1 flex flex-col justify-between">
                                <?php
                                $related_cats = get_the_terms($related->ID, 'product_cat');
                                $child_cats = collect($related_cats)->filter(function ($cat) {
                                    return $cat->parent !== 0;
                                });
                                ?>
                                @if ($child_cats && $child_cats->count())
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ implode(', ', $child_cats->pluck('name')->toArray()) }}
                                    </div>
                                @endif
                                <h2 class="text-xl font-semibold mb-2">{{ get_the_title($related->ID) }}</h2>
                                <span class="proline-persimmon-text block w-full text-left cursor-pointer"
                                    onclick="window.location.href='/contact-us/'">Order a free measure and quote</span>
                                <button onclick="window.location.href='{{ get_permalink($related->ID) }}'"
                                    class="proline-persimmon-button-woocommerce-button bg-proline-gray text-proline-dark py-2 hover:bg-white w-full mt-2">
                                    Learn More
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
