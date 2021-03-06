<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //为前三个用户生成共100条微博
        $user_ids = range(1,3);
        $faker = app(Faker\Generator::class);
        $statuses = factory(\App\Models\Status::class)->times(100)->make()->each(function($status) use($faker,$user_ids){
           $status->user_id = $faker->randomElement($user_ids);
        });

        \App\Models\Status::insert($statuses->toArray());
    }
}
