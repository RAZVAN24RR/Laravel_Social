@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            <textarea name="content" class="form-control" placeholder="Write a message"></textarea>
            <button type="submit" class="btn btn-primary mt-2">Post</button>
        </form>

        <div class="messages mt-4">
            @foreach($messages as $message)
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>{{ $message->user->name }} · {{ $message->created_at->diffForHumans() }}</h6>
                        <p>{{ $message->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
