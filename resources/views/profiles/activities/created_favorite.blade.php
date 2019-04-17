@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} favored a reply to the thread titled: {{$activity->subject->favorited->thread->title}}
        </a>
        {{--@dd($activity->subject->thread->title)--}}
        {{--        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>--}}
    @endslot
    @slot('body')
        {{$activity->subject->favorited->body}}
    @endslot
@endcomponent
