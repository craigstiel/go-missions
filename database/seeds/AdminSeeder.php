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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('adminadmin'),
            'is_master' => true,
            'email_verified_at' => \Illuminate\Support\Facades\DB::raw('current_timestamp'),
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
