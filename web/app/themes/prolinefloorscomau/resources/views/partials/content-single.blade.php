<article @php(post_class('h-entry'))>
    <header>
        <div class="proline-section-dark-vanilla proline-section-no-margin proline-section-padding">
            <a href="/blog/" class="text-proline-persimmon">All Posts</a>
            <h1 class="p-name proline-section-large-text pt-6">
                {!! $title !!}
            </h1>
            @if (has_post_thumbnail())
                <div class="w-full mt-6 relative">
                    {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full min-h-[360px] max-h-[100%] object-cover']) !!}

                </div>
                <div class="flex justify-end space-x-4 py-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(get_permalink()) }}"
                        target="_blank" rel="noopener" class="hover:scale-110 transition">
                        <img src="@asset('resources/images/layout/facebook-logo.png')" alt="Share on Facebook" class="h-8 w-8" />
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(get_permalink()) }}&title={{ urlencode($title) }}"
                        target="_blank" rel="noopener" class="hover:scale-110 transition">
                        <img src="@asset('resources/images/layout/linkedin-logo.png')" alt="Share on LinkedIn" class="h-8 w-8" />
                    </a>
                </div>
            @endif
        </div>
    </header>

    <div class="e-content">
        <div class="proline-section-silk proline-section-no-margin proline-section-padding">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2">
                    @php(the_content())
                </div>
                <aside class="md:col-span-1">
                    <div class="bg-proline-dark-vanilla p-6 flex flex-col">
                        <h2 class="text-lg font-semibold mb-6">Join our Newsletter</h2>
                        <p class="text-sm mb-4">Get the latest updates and blog posts delivered directly to your inbox.
                        </p>
                        <form action="#" method="POST" class="w-full flex flex-col space-y-2">
                            <input type="email" name="email" placeholder="Your Email Address"
                                class="flex-1 px-4 py-2 bg-gray-100 border-b-1 border-gray-300 bg-proline-dark-vanilla text-proline-gray dark:border-gray-600 dark:placeholder-gray-900 w-full mb-2 sm:mb-0">
                            <button type="submit"
                                class="mt-6 px-4 py-2 cursor-pointer w-full border-1 border-gray-600">Subscribe</button>
                        </form>
                        <p class="text-xs mt-4">By subscribing, you agree to
                            our <a href="#" class="text-proline-persimmon hover:underline">Privacy Policy</a>.</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @if ($pagination())
        <footer>
            <nav class="page-nav" aria-label="Page">
                {!! $pagination !!}
            </nav>
        </footer>
    @endif
</article>
