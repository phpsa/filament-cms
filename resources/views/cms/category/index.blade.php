
<div class="container">
    <h2>Topics</h2>
    @foreach ($categories as $category)
    <a href="{{ route('phpsa.filament.cms.resources.categories.resource.show', $category)}}">{{ $category->name }}</a>
    @endforeach
    {{ $categories->links() }}
</div>

