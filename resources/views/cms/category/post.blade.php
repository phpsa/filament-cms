
<div class="container">

    <h2>{{$post->name}}</h2>
    <em>{{$topic->name}}</em>

    ##
    E: {{ $post->excerpt }}
##
 B: {{$post->node('content')}}
 ##

</div>

