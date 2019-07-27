<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = factory(App\Models\User::class)->times(50)->make();
        \App\Models\User::insert($users->makeVisible(['password','remember_token'])->toArray());

        $user = \App\Models\User::first();
        $user->name = 'Mr.yang';
        $user->email = 'yangzhiweiga@163.com';
        $user->password = bcrypt('123456');
        $user->is_admin = true;
        $user->save();
    }
}
