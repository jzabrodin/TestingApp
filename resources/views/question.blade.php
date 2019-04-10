@extends('basic')

@section('content')

<div>
<h3>Количество баллов : {{session("total_points")}}</h3>
</div>
<div><h1>Вопрос № {{$question[0]->id}}</h1></div>

<div class="jumbotron">
    <form action="/question/{$id}" method="post">

        @csrf

        {{--
        {{$question[0]->type}}
        @foreach ($parameters as $item)
            {{$item}}
        @endforeach --}}

    <h1 class="display-4">{{$question[0]->name}}
        @if ($question[0]->type == "VALUE")
            {{$parameters[0]}}+{{$parameters[1]}}
        @endif
    </h1>
            <p class="lead">{{$question[0]->description}}</p>

        @if ($question[0]->type == "None")

        @elseif ($question[0]->type == "VALUE")

            <input type="hidden" name="V1" value="{{$parameters[0]}}">
            <input type="hidden" name="V2" value="{{$parameters[1]}}">
            <input type="number" name="RES" id="Result">

        @elseif ($question[0]->type == "CHECKBOX")

            @foreach ($parameters as $item)
                <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="{{$item->answer}}" id="{{$item->answer}}">
                    <label class="custom-control-label" for="{{$item->answer}}">{{$item->answer}}</label>
                </div>
            @endforeach

        @elseif ($question[0]->type == "WEEKDAY")

            @foreach ($parameters as $item)
                <div class="custom-control custom-radio">
                    <input type="radio" id="{{$item}}" name="radio" value="{{$item}}" class="custom-control-input">
                <label class="custom-control-label" for="{{$item}}">{{$item}}</label>
                </div>
            @endforeach

        @endif


        <input type="submit" value="Следующий вопрос">

    </form>
</div>

@endsection
