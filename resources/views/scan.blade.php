@extends('layouts.app')
@section('content')
    <ais-instant-search index-name="threads" :search-client="searchClient">
        <div class="left-panel">
            <ais-clear-refinements />
            <h2>Channel</h2>
            <ais-refinement-list attribute="threads.channel" searchable />
            <ais-configure :hitsPerPage="8" />
        </div>
        <div class="right-panel">
            <ais-search-box />
            <ais-hits>
                <div slot="threads" slot-scope="{ threads.title }">
                    <h2>{{ threads.title }}</h2>
                </div>
            </ais-hits>
            <ais-pagination />
        </div>
    </ais-instant-search>
@endsection