<?php
$footerMenu = [
    [
        'title' => 'Quick Links',
        'items' => [['text' => 'Shop All', 'url' => '#'], ['text' => 'Order Free Samples', 'url' => '#'], ['text' => 'Stair Nosing and Accessories', 'url' => '/stair-nosing/'], ['text' => 'Gallery', 'url' => '/galleries/'], ['text' => 'Where to Buy', 'url' => '/where-to-buy'], ['text' => 'Contact Us', 'url' => '/contact-us']],
    ],
    [
        'title' => 'Resources',
        'items' => [['text' => 'Support', 'url' => '#'], ['text' => 'Care Tips', 'url' => '#'], ['text' => 'Installation', 'url' => '#'], ['text' => 'Flooring Specs', 'url' => '#'], ['text' => 'Brochures', 'url' => '#'], ['text' => 'Blog', 'url' => '#']],
    ],
    [
        'title' => 'Contact Us',
        'items' => [['text' => 'Follow Us', 'url' => '#'], ['text' => 'Social Media', 'url' => '#'], ['text' => 'Newsletter', 'url' => '#'], ['text' => 'Special Offers', 'url' => '#'], ['text' => 'Request Free Samples', 'url' => '#'], ['text' => 'Request Measure and Quote', 'url' => '#']],
    ],
    [
        'title' => 'About Us',
        'items' => [['text' => 'Why Proline Floors', 'url' => '#'], ['text' => 'Our Process', 'url' => '#'], ['text' => 'Our Team', 'url' => '#'], ['text' => 'Mission Statement', 'url' => '#']],
    ],
];
?>
<footer class="bottom-0 left-0 z-20 w-full p-4 bg-proline-dark border-t-1 border-proline-silk">
    @php(dynamic_sidebar('sidebar-footer'))
    <div class="mx-auto w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-12 px-6 py-6 lg:py-8">
            @foreach ($footerMenu as $section)
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-proline-gray uppercase">{{ $section['title'] }}</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        @foreach ($section['items'] as $item)
                            <li class="mb-4">
                                <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['text'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            <div class="col-span-2">
                <h2 class="mb-6 text-sm font-semibold text-proline-gray uppercase">Subscribe</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium">
                    Join our newsletter for updates on products and promotions.</p>
                <form action="#" method="POST" class="flex flex-col sm:flex-row sm:space-x-4 mt-6 w-full">
                    <input type="email" name="email" placeholder="Your Email Here"
                        class="flex-1 px-4 py-2 text-gray-900 bg-gray-100 border border-gray-300 bg-proline-dark text-proline-gray dark:border-gray-600 dark:placeholder-gray-400 w-full mb-2 sm:mb-0">
                    <button type="submit"
                        class="bg-proline-dark border-1 border-proline-gray text-proline-gray px-4 py-2 hover:bg-black uppercase cursor-pointer w-full sm:w-auto">
                        Subscribe
                    </button>
                </form>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-4">
                    By subscribing, you consent to receive updates and agree to our <a href="#"
                        class="text-proline-gray hover:underline">Privacy
                        Policy</a>.
                </p>
            </div>
        </div>

        <div class="px-6 py-6 bg-proline-dark">
            <img class="h-auto max-w-full" src="@asset('resources/images/layout/logo-proline-floors-footer-group.png')" class="h-4"
                alt="{!! $siteName !!} Footer Logo" />
        </div>

        <div class="grid grid-cols-2 gap-18 mt-18 mx-6 mb-9 py-6 border-b-1 border-t-1 border-proline-silk">
            <div class="flex flex-col justify-between">
                <h2 class="mb-6 text-sm font-semibold text-proline-gray uppercase">
                    Proline Floors & Bostik Australia - A Strong Partnership
                </h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium">
                    Proline Floors is a proud partner and supplier of Bostik Australia, a leading name in innovative
                    adhesive and flooring solutions. This partnership allows us to offer a complete flooring system,
                    ensuring quality, durability, and seamless installation for our customers.
                </p>
            </div>
            <div class="flex flex-col justify-between">
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-6">
                    By working closely with Bostik, we provide industry-leading products that enhance the performance
                    and longevity of our flooring solutions. Whether it's adhesives, primers, or levelling compounds,
                    Bostik products complement our range, delivering trusted and reliable results for both residential
                    and commercial applications.
                </p>
            </div>
        </div>

        <div class="px-6 py-6 bg-proline-dark">
            <div class="flex flex-col sm:flex-row sm:justify-between items-center space-y-4 sm:space-y-0">
                <span class="text-sm text-gray-500 dark:text-gray-300 sm:text-center">&copy; {{ date('Y') }}
                    Proline
                    Floors. All Rights Reserved.</span>

                <div class="flex space-x-5 rtl:space-x-reverse">
                    <a href="#" class="text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">Privacy
                        Policy</a>
                    <a href="#" class="text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">Terms of
                        Use</a>
                    <a href="#" class="text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">Cookie
                        Policy</a>
                </div>

                <div class="flex space-x-5 rtl:space-x-reverse items-middle">
                    <a href="https://www.facebook.com/prolinefloorsaustralia"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white" target="_blank">
                        <img class="h-auto max-w-full" src="@asset('resources/images/layout/facebook-logo.png')" class="h-4" alt="Facebook" />
                        <span class="sr-only">Facebook page</span>
                    </a>
                    <a href="https://www.instagram.com/prolinefloors/"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white" target="_blank">
                        <img class="h-auto max-w-full" src="@asset('resources/images/layout/instagram-logo.png')" class="h-4" alt="Instagram" />
                        <span class="sr-only">Instagram page</span>
                    </a>
                    <a href="https://www.linkedin.com/company/proline-floors/"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white" target="_blank">
                        <img class="h-auto max-w-full" src="@asset('resources/images/layout/linkedin-logo.png')" class="h-4" alt="LinkedIn" />
                        <span class="sr-only">LinkedIn page</span>
                    </a>
                    <a href="https://www.youtube.com/channel/UCLH68GbsSsxoJm1JgfS-wKA"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white" target="_blank">
                        <img class="h-auto max-w-full" src="@asset('resources/images/layout/youtube-logo.png')" class="h-4" alt="YouTube" />
                        <span class="sr-only">YouTube page</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>
