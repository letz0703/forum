<reply inline-template :attributes="{{$reply}}" v-cloak>
    <div class="card" id="reply-{{$reply->id}}">
        <div class="card-header">
            <div class="level">
                <h6 class="flex"><a href="{{route('profile',$reply->owner)}}"> {{ $reply->owner->name }} </a>
                    said... {{ $reply->created_at->diffForHumans() }}
                </h6>
                @if (Auth::check())
                    <favorite :reply="{{$reply}}"></favorite>
                @endif
            </div>
        </div>

        <div class="card-body">
            <article>
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" v-text="body"></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm mr-1" @click="update">update</button>
                    <button class="btn btn-link btn-sm" @click="editing=false">cancel</button>
                </div>
                <div v-else>
                    <div class="body" v-text="body"></div>
                </div>
            </article>
            <hr>
        </div>
        @can('update',$reply)
            <div class="card-footer level">
                <button class="btn btn-sm btn-default mr-1" @click="editing=true">edit</button>
                <button class="btn btn-sm btn-danger" @click="destroy">delete</button>
            </div>
        @endcan
    </div>
</reply>