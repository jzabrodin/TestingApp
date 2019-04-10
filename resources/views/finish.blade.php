@extends('basic')

@section('content')

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
        <h1 class="display-3">Поздравляем, {{session("email")}}</h1>
            <img src="{{asset("".$filename."")}}" alt=""/>
            <hr class="my-2">
            <p>Твоя оценка {{session('total_points',0)}} баллов</p>
            <p>Общее время прохождения :{{$delta}} секунд</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="/" role="button">Начать сначала</a>
            </p>
        </div>
    </div>

@endsection
