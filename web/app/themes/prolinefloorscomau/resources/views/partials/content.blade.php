<article @php(post_class())>
    <header>
        @if (has_post_thumbnail())
            <a href="{{ get_permalink() }}" class="block mb-6">
                {!! get_the_post_thumbnail(null, 'large', [
                    'class' => 'w-full h-full min-h-[360px] max-h-[100%] object-cover',
                ]) !!}
            </a>
        @endif
        <h2 class="entry-title proline-section-large-text">
            <a href="{{ get_permalink() }}">
                {!! $title !!}
            </a>
        </h2>
    </header>
    <div class="entry-summary mb-4">
        @php(the_excerpt())
    </div>
    <a href="{{ get_permalink() }}" class="inline-block text-proline-persimmon py-2 tracking-wide">Read More</a>
</article>
