<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->insertGetId([
            'name' => Str::random(10),
            'email' => 'admin@gmail.com',
            'password' => bcrypt('adminadmin'),
            'is_master' => true,
            'api_token' => 'UktlY6Ov2aidQyuu4gKN1CdYdyTeennrWCuJoOB7Xyr6vTf4k3gLruqJ46nJrJ5u',
            'created_at' => \Illuminate\Support\Facades\DB::raw('current_timestamp'),
        ]);

        DB::table('user_position')->insert([
            'user_id' => $user,
            'position' => 'admin',
            'is_active' => true,
            'created_at' => \Illuminate\Support\Facades\DB::raw('current_timestamp'),
        ]);

        DB::table('system')->insert([
            'multiple' => true,
            'created_at' => \Illuminate\Support\Facades\DB::raw('current_timestamp'),
        ]);
    }
}
