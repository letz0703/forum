@extends('layouts.app')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
{{--@endsection--}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">Create a new thread</div>


                    <div class="card-body">
                        <form action="/threads" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="channel_id">Choose Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control">
                                    <option value="">Choose One</option>
                                    {{--                                    @foreach($channels as $channel)--}}
                                    @foreach(App\Channel::all() as $channel)
                                        <option value="{{ $channel->id }}"
                                                {{ old('channel_id') == $channel->id? 'selected':'' }}
                                        >{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <wysiwyg name="body"></wysiwyg>
                                {{--<textarea name="body" id="body" class="form-control"--}}
                                          {{--rows="8">{{ old('body') }}</textarea>--}}
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LeLrJQUAAAAANzi8bZci3loY-ODam1307y9uDph"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>
                            @if(count($errors))
                                @foreach($errors->all() as $error)
                                    <div>
                                        <ul class="alert alert-danger">
                                            <li>{{$error}}</li>
                                        </ul>
                                    </div>
                                @endforeach
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
