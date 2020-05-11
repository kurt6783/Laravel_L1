<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        //
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'rember_tolen'])->toArray());

        $user = User::find(1);
        $user->name = 'kurt';
        $user->email = 'k0911111111@gmail.com';
        $user->is_admin = true;
        $user->save();
    }
}
