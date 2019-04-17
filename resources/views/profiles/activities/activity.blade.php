<div class="card">
    <div class="card-header level">
        <span class="flex">
            {{ $heading }}
       </span>
        <span> {{--{{ $thread->created_at->diffForHumans() }}--}} </span>
    </div>
    <div class="card-body">
        <div class="body">
            {{ $body }}
        </div>
    </div>
</div>
<br>