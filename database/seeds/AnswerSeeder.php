<?php

use Illuminate\Database\Seeder;
use \App\Answer;
use \App\Question;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $q = new \App\Question;
        $q -> name = "Тестовый вопрос";
        $q -> type = "NULL";
        $q -> description = "Это тестовый вопрос, Вы заработаете 1 балл просто нажав продолжить, удачи!";
        $q -> course_id = 0;
        $q -> save();

        $q = new \App\Question;
        $q -> name = "Пожалуйста введите в поле ввода результат вычисления выражения";
        $q -> type = "VALUE";
        $q -> description = "Вычислите выражение и заработаете 1 балл!";
        $q -> course_id = 0;
        $q -> save();

        $q = new \App\Question;
        $q -> name = "Какие языки программирования Вы знаете?";
        $q -> type = "CHECKBOX";
        $q -> description = "Вам необходимо отметить языки программирования которыми Вы владеете.";
        $q -> course_id = 0;
        $q -> save();

        $a = new \App\Answer;
        $a -> question_id = $q->id;
        $a -> isCorrect = 1;
        $a -> answer = "PHP";
        $a -> save();

        $a = new \App\Answer;
        $a -> question_id = $q->id;
        $a -> isCorrect = 1;
        $a -> answer = "Python";
        $a -> save();

        $a = new \App\Answer;
        $a -> question_id = $q->id;
        $a -> isCorrect = 1;
        $a -> answer = "JS";
        $a -> save();

        $a = new \App\Answer;
        $a -> question_id = $q->id;
        $a -> isCorrect = 1;
        $a -> answer = ".NET";
        $a -> save();

        $a = new \App\Answer;
        $a -> question_id = $q->id;
        $a -> isCorrect = 0;
        $a -> answer = "Visual Basic";
        $a -> save();

        $q = new \App\Question;
        $q -> name = "Какой сегодня день недели?";
        $q -> type = "WEEKDAY";
        $q -> description = "Если Вы угадаете какой сегодня день недели, то Вас ждет незабываемый приз... 1 балл!";
        $q -> course_id = 0;
        $q -> save();

    }
}
