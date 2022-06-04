<div class="container">
    <h2>{{ $category->name }}</h2>
    @foreach ($posts as $post)
        <a
            href="{{ route('phpsa.filament.cms.resources.topic.blog.post.resource', ['page' => $post->slug, 'topic' => $category->slug]) }}">{{ $post->name }}</a>
    @endforeach
    {{ $posts->links() }}
</div>
