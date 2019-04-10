<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Result;

class ResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        for ($i=0; $i < 40; $i++) {

            $result = new \App\Result;
            $result->email      = $faker->safeEmail;
            $result->start_time = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $result->end_time   = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $result->points     = $faker->randomDigit;
            $result->save();
        }

    }
}
