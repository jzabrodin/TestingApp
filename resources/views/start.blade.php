@extends('basic');

@section('content')

    <div class="jumbotron">
        <h2>Старт!</h2>

        <form class="" action="/beginTesting" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-row">
            <div class="col">
                <label for="emailAdress">E-mail</label>
                <input id="emailAdress" class="form-control" placeholder="Введите адрес электронной почты" type="email" name="email" id="email">
            </div>
            <div class="col">
                <label for="avatar">Выберите аватар!</label>
                <input
                       type="file"
                       id="avatar"
                       name="avatar"
                       class="form-control"
                       accept="image/png, image/jpeg"/>
            </div>
            </div>
            <input class="btn btn-primary" type="submit" value="Вперед!">

        </form>

    </div>


        <table class="table">
            <tbody>
                <thead>
                    <tr>
                        <th colspan="5">Таблица результатов</th>
                        {{$results->links()}}
                    </tr>
                </thead>
                <tr>
                    <th>E-mail</th>
                    <th>Баллы</th>
                    <th>Время начала</th>
                    <th>Время завершения</th>
                    <th>Продолжительность</th>
                </tr>
                @foreach ($results as $result)

                    <tr>
                        <td>{{$result->email}}</td>
                        <td>{{$result->points}}</td>
                        <td>{{$result->start_time}}</td>
                        <td>{{$result->end_time}}</td>
                        <td>{{strtotime($result->end_time) - strtotime($result->start_time) }} seconds</td>
                    </tr>

                @endforeach


            </tbody>
        </table>
        {{$results->links()}}
    </div>


@endsection
