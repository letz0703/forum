@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                @include('threads._list')
                {{ $threads->render() }}
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Search
                    </div>
                    <div class="card-body">
                        <form action="/threads/search" method="get">
                            <div class="form-group">
                                <input type="text" placeholder="Search for Something"
                                       class="form-control"
                                       name="q"
                                >
                            </div>
                            <div class="form-group">
                                <button class="btn-sm" type="submit">Search</button>
                            </div>

                        </form>
                    </div>
                </div>
                @if (count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            @foreach( $trending as $thread)
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <a href="{{ url($thread->path) }}">
                                            <li>{{ $thread->title }}</li>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
            </div>
            @endif
        </div>
    </div>
@endsection
