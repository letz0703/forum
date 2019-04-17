@forelse($threads as $thread)
    <div class="card">
        <div class="card-header level">
            <div class="flex">
                @if ( auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                    <h4 class="flex">
                        <a href="{{$thread->path()}}">{{$thread->title}}</a>
                        <span class="fa fa-bell btn-sm"></span>
                    </h4>
                @else
                    <h4>
                        <a href="{{$thread->path()}}">{{$thread->title}}</a>
                    </h4>
                @endif
                <h5> Posted By:
                    {{--@dd($thread->creator->name)--}}
                    <a href="{{ route('profile', $thread->creator) }}">
                    {{ $thread->creator->name }}
                    {{--</a>--}}
                </h5>
            </div>
            <a href="{{ $thread->path() }}"><strong>{{$thread->replies_count}} {{str_plural('reply',$thread->replies_count)}}</strong></a>
        </div>
        <div class="card-body">
            {{--<div class="body"> {{$thread->body}} </div>--}}
            <div class="body"> {!! $thread->body !!} </div>
        </div>
        <div class="card-footer">
            {{ $thread->visits }} visits
        </div>
    </div>
    <br>
@empty
    <p>No relavant result now</p>
@endforelse