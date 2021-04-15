<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersProfilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();

        $userId = DB::table('users')->insertGetId([
		    'name' => 'Admin Lastname',
			'username' => 'Admin Lastname',
			'slug' => 'admin',
			'can_accept_payments' => 1,
			'slug' => 'admin',
            'email' => 'admin@afiaanyi.com',
			'is_admin' => 1,
			'verified' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('changeme'),
        ]);

        $userIdB = DB::table('users')->insertGetId([
		    'name' => 'User User',
			'username' => 'User User',
			'slug' => 'user',
			'can_accept_payments' => 1,
			'slug' => 'user',
            'email' => 'user@afiaanyi.com',
			'is_admin' => 0,
      'is_trader' => 1,
			'verified' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('changeme'),
        ]);

		/*
        DB::table('profiles')->insert([
            'user_id' => $userId,
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'tel' => '08023747488',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
		*/
    }
}
