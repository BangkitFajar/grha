<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
public function run()
    {
        DB::table('users')->insert([
            'name' => 'ntap',
            'email' => 'ntap@gmail.com',
            'password' => app('hash')->make('ntap'),
        ]);
}

}