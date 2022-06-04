<div class="container">

    <h2>{{ $page->name }}</h2>
    <em>{{ $topic->name }}</em>

    ##
    E: {{ $page->excerpt }}
    ##
    B: {{ $page->node('content') }}
    ##

</div>
