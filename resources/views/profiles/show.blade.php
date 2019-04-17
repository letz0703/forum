@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <avatar-form :user = "{{ $profileUser }}"></avatar-form>

                </div>
                <small> last visit: {{ $profileUser->updated_at->diffForHumans() }}</small>


                {{--                @foreach($threads as $thread)--}}
                @forelse($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach ($activity as $record)
                        @if ( view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}",[ 'activity'=> $record ])
                        @endif
                    @endforeach
                @empty
                    <p>no activity for this user yet</p>
                @endforelse
                {{--{{ $threads->links() }}--}}
            </div>
        </div>
    </div>
@endsection
