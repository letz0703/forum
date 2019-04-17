@extends('layouts.app')
@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view inline-template
                 :thread="{{ $thread }}"
                 {{--:data-replies-count="{{ $thread->replies_count }}"--}}
                 {{--:data-locked = "{{ $thread->locked }}"--}}
    >
        <div class="container">
            {{--<div class="row justify-content-center">--}}
            <div class="row">
                <div class="col-md-8" v-cloak>
                    @include('threads._question')
                    <replies @added="repliesCount++" @deleted="repliesCount--"></replies>
                    {{--@foreach ($replies as $reply)--}}
                    {{--@include('threads.reply')--}}
                    {{--@endforeach--}}
                    {{--{{ $replies->links() }}--}}


                </div>
                <div class="col-md-4">
                    <div class="card">
                        {{--<div class="card-header">--}}
                        {{--<a href="#">--}}
                        {{--{{ $thread->creator->name }}--}}
                        {{--</a>--}}
                        {{--posted:--}}
                        {{--{{$thread->title}}--}}
                        {{--</div>--}}
                        <div class="card-body">
                            <div class="body"> This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="#">{{ $thread->creator->name }} </a>
                                {{--                                and has {{ $thread->replies()->count() }} comments.--}}
                                @if(!$thread->replies_count > 0)
                                    and has no comment yet;
                                @else { and
                                has <span
                                        v-text="repliesCount"></span> {{ str_plural('comment',$thread->replies_count) }}
                                .
                                }
                                @endif
                            </div>
                            <div class="level">
                                <subscribe-button :active="{{ $thread->isSubscribedTo?'true':'false' }}" class="mr-1"
                                v-if="signedIn"></subscribe-button>
                                <button class="btn btn-sm btn-outline-dark"
                                        v-if="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked?'unlock':'lock'"
                                ></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
