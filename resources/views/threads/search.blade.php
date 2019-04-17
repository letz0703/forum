@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row ">
            <ais-index
                    api-id="{{ config('scout.algolia.id') }}"
                    api-key="{{ config('scout.algolia.id') }}"
                    index-name="threads"
                    query="{{ request('q') }}"
            >
                <div class="col-md-8 col-md-offset-2">
                    <ais-results>
                        <template scope="{result}">
                            <li>
                                <a href="" >
                                    <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                </a>
                            </li>
                        </template>
                    </ais-results>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Search
                        </div>
                        <card-body>
                            {{--<ais-search-box placeholder="Find threads..." :autofocus="true">--}}
                            <ais-search-box>
                                <ais-input placeholder="Find Threads" :autofocus="true"></ais-input>
                            </ais-search-box>
                        </card-body>
                    </div>
                    <div class="card">
                        <card-header>
                            Filter By Channel
                        </card-header>
                        <div class="card-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                        </div>
                    </div>

                    @if (count($trending))
                        <div class="card">
                            <div class="card-header">
                                Trending Threads
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach( $trending as $thread)
                                        <li class="list-group-item">
                                            <a href="{{ url($thread->path) }}">
                                                {{ $thread->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </ais-index>
        </div>
    </div>
@endsection
