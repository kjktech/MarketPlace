<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	\DB::table('banks')->delete();

    	\DB::table('banks')->insert(array (
    		0 =>
    		array (
    			'id' => 1,
    			'name' => 'Access Bank Plc',
    			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    			'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    		),
    		1 =>
    		array (
    			'id' => 2,
    			'name' => 'Citibank Nigeria Limited',
    			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    			'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    		),
    		2 =>
    		array (
    			'id' => 3,
    			'name' => 'Diamond Bank Plc',
    			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    			'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    		),
        3 =>
        array (
          'id' => 4,
          'name' => 'Ecobank Nigeria Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        4 =>
        array (
          'id' => 5,
          'name' => 'Fidelity Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        5 =>
        array (
          'id' => 6,
          'name' => 'FIRST BANK NIGERIA LIMITED',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        6 =>
        array (
          'id' => 7,
          'name' => 'First City Monument Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        7 =>
        array (
          'id' => 8,
          'name' => 'Guaranty Trust Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        8 =>
        array (
          'id' => 9,
          'name' => 'Heritage Banking Company Ltd.',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        9 =>
        array (
          'id' => 10,
          'name' => 'Key Stone Bank',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        10 =>
        array (
          'id' => 11,
          'name' => 'Polaris Bank',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        11 =>
        array (
          'id' => 12,
          'name' => 'Providus Bank',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        12 =>
        array (
          'id' => 13,
          'name' => 'Stanbic IBTC Bank Ltd.',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        13 =>
        array (
          'id' => 14,
          'name' => 'Standard Chartered Bank Nigeria Ltd.',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        14 =>
        array (
          'id' => 15,
          'name' => 'Sterling Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        15 =>
        array (
          'id' => 16,
          'name' => 'SunTrust Bank Nigeria Limited',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        16 =>
        array (
          'id' => 17,
          'name' => 'Union Bank of Nigeria Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        17 =>
        array (
          'id' => 18,
          'name' => 'United Bank For Africa Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        18 =>
        array (
          'id' => 19,
          'name' => 'Unity Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        19 =>
        array (
          'id' => 20,
          'name' => 'Wema Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),
        20 =>
        array (
          'id' => 21,
          'name' => 'Zenith Bank Plc',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ),

    	));

    }
}
