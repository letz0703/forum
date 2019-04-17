{{--Editing View--}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">

            <input type="text" v-model="form.title" class="form-control">

            @can('update',$thread)
                <form method="post" action="{{ $thread->path() }}" class="ml-auto">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">
                        Delete Thread
                    </button>

                </form>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
            {{--<textarea class="form-control" rows="10" v-model="form.body"></textarea>--}}
        </div>
        <hr>
    </div>
    <div class="card-footer">
        <button class="btn btn-sm level-item" @click="editing=true" v-if="! editing">Edit</button>
        <button class="btn btn-sm btn-primary level-item" @click="update">Update</button>
        <button class="btn btn-sm level-item" @click="resetForm">Cancel</button>
    </div>
</div>

{{--View Mode--}}
<div class="card" v-else>
    <div class="card-header level">
        <img src="{{ asset($thread->creator->avatar_path) }}"
             alt="{{$thread->creator->name}}"
             width="25" height="25"
             class="mr-1"
        >
        <span class="flex">
                            <a href="{{ route('profile',$thread->creator) }}">
                                {{ $thread->creator->name }}
                            </a>
                            posted : <span v-text="title"></span>
                        </span>
        @can('update',$thread)
            <form method="post" action="{{ $thread->path() }}">
                @csrf
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-link">
                    Delete Thread
                </button>

            </form>
        @endcan

    </div>
    <div class="card-body">
        <article>
            <div class="body" v-html="body"></div>
        </article>
        <hr>
    </div>
    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-sm" @click="editing=true">Edit</button>
    </div>
</div>
