<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;
use \App\Result;

class PageController extends Controller
{
    //стартовая страница, откуда начинается путешествие
    public function start()
    {
        $results = Result::paginate(10);
        return view("start", ['results' => $results]);
    }

    //первый пост запрос, инициализируем сессию и проверяем почту
    public function beginTesting(Request $request)
    {

        if (is_null($request->email) ){
            $request->email = "";
        }

        //dd($request);

        $request->validate([$request->email], [
            'email' => 'bail|required|email'
        ]);

        $time = time();

        //помещаем картинку в хранилище
        if ($request->allFiles()){
            $path = $request->file('avatar')->storeAs(
                'public', md5($request->email)
            );
        }

        //инициализация сессии
        $request->session() -> flush();

        $request -> session() -> put("email" , $request->email);
        $request -> session() -> put("current_id", 1);
        $request -> session() -> put("total_points", 0);
        $request -> session() -> put("started_at", $time );

        return redirect("/question/1");

    }

    public function saveResult($request,$started_at,$finished_at)
    {
        $result = new \App\Result;
        $result->start_time = date('Y-m-d H:i:s',$started_at);
        $result->end_time = date('Y-m-d H:i:s',$finished_at);
        $result->email = $request->session()->get("email","");
        $result->points = $request->session()->get("total_points",0);
        $result->save();
    }

    public function getQuestionById($id){
        return \App\Question::where('id',$id)->take(1)->get();
    }

    public function getAnswersByQuestionId($id){
        return \App\Answer::where('question_id',$id)->take(10)->get();
    }

    public function getCurrentWeekDay()
    {
        $today    = new \DateTime();
        $weekDay1 = (int)$today->format("N");

        $intWeekday = (int)$weekDay1;
        return $intWeekday;
    }

    public function getWeekDays()
    {
        return ["","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Воскресенье"];
    }

    public function getQuestionParameters($question){

        if ( sizeof($question) == 0){
            return [];
        }

        $currentQuestion = $question[0];

        if ($currentQuestion->type == "NULL"){

            return [];

        } else if ($currentQuestion->type == "VALUE"){

            $array = [random_int(10,99),random_int(10,99)];
            return $array;

        } else if ($currentQuestion->type == "WEEKDAY"){

            $weekDays = $this->getWeekDays();
            $intWeekday = $this->getCurrentWeekDay();

            //массив для уникальных значений
            $array = [];

            //массив который позже передадим в форму
            $daysArray = [];

            //добавляем текущий день
            array_push($daysArray,$weekDays[$intWeekday]);
            array_push($array,$intWeekday);


            while (True){

                if (sizeof($daysArray) == 4){break;}

                $random = random_int(1,7);

                if ( array_search($random,$array) ){continue;}

                array_push($array,$random);
                array_push($daysArray,$weekDays[$random]);

            }

            shuffle($daysArray);

            return $daysArray;

        } else if ($currentQuestion->type = "CHECKBOX"){

            return $this->getAnswersByQuestionId($currentQuestion->id);

        } else {
            return [];
        }
    }

    // get запрос который отображает страницу с вопросом
    public function question(Request $request,$id)
    {

        $question = $this->getQuestionById($id);

        // если вопросов больше нет, идем на финишную страницу
        if ( sizeof($question ) == 0 ){

            $started_at  = $request->session() -> get("started_at", 0);
            $finished_at = time();
            $delta       = $request->session()->get("delta",0);

            //если в сессии отсутствует значение, то берем текущую дату
            //и считаем ее датой завершения
            if ($delta == 0){
                $delta       = $finished_at - $started_at;
                $request->session()->put("delta",$delta);
                $this->saveResult($request,$started_at,$finished_at);
            }

            //получаем путь к файлу на основании почты и передаем его в шаблон
            $path_to_file = md5($request->session()->get("email"));
            $filename    = Storage::url($path_to_file);

            return view("finish",['delta'=>$delta,'filename'=>$filename]);

        //если пробовали перейти на страницу с вопросом, id которого отличается от id текущего
        //то переадресовываем на страницу с текущим id
        } else if ( $id != session("current_id") ){
            $current_id = $request->session()->get("current_id");
            return redirect("/question/".$current_id);

        } else {
            $parameters = $this->getQuestionParameters($question);
            return view("question", ["question" => $question,"parameters"=>$parameters]);
        }

    }

    public function addPoint($request)
    {
        $current_points = $request->session() -> get("total_points");
        $current_points++;
        $request->session()->put("total_points",$current_points);
    }

    public function checkAnswer($request,$questions)
    {

        if (sizeof($questions) == 0){
            return;
        }

        $current_question = $questions[0];

        if ($current_question->id == 1 or $current_question->type == "NULL"){ // или тип NULL
            $this->addPoint($request);
        } else if ($current_question->id == 2 or $current_question->type == "VALUE"){

            $v1 = (int) $request->V1;
            $v2 = (int) $request->V2;
            $res = (int) $request->RES;

            if( ($v1 + $v2) == $res ){
                $this->addPoint($request);
            }

        } else if ($current_question->id == 3 or $current_question->type == "CHECKBOX"){
            $array = $request->except("_token");

            $flag = false;

            //если возле любого языка кроме Basic стоит галочка, устанавливаем флаг
            foreach ($array as $key => $value) {
                if($key != "Visual Basic" and $value == "on"){
                    $flag = true;
                    break;
                }
            }

            $flagBasic = ( isset($array["Visual_Basic"] ) and ($array["Visual_Basic"] == "on") );
            //если Васек пролетел и любой другой язык стоит, добавляем балл

            if( !$flagBasic and $flag){
                $this->addPoint($request);
            }

        } else if($current_question->id == 4 or $current_question->type == "WEEKDAY"){
            $arr = $request->except("_id");

            if (!isset($arr["radio"])){
                return;
            }

            $dayNumber = $this->getCurrentWeekDay();
            $days      = $this->getWeekDays();

            if ($days[$dayNumber] == $arr["radio"]){
                $this->addPoint($request);
            }

        }
    }

    //проверка вопроса и переход к следующему ( или переход на страницу завершения теста )
    public function checkQuestion(Request $request , $id){

        $current_id = $request->session() -> get("current_id");
        $questions  = $this->getQuestionById($current_id);

        $this->checkAnswer($request,$questions);

        $current_id++;
        $request->session()->put("current_id",$current_id);

        return redirect("/question/".$current_id);

    }

}
