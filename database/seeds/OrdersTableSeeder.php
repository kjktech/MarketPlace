<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'listing_id' => NULL,
                'seller_id' => NULL,
                'user_id' => 3,
                'email' => 'user@afiaanyi.com',
                'last_name' => 'Sayo',
                'status' => 'open',
                'amount' => '30.00',
                'currency' => NULL,
                'reference' => '4hDH6kGk8sg6jpRsthiCpYVkL',
                'listing_options' => NULL,
                'choices' => NULL,
                'customer_details' => NULL,
                'accepted_at' => NULL,
                'declined_at' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2019-01-15 13:37:19',
                'updated_at' => '2019-01-15 13:37:19',
                'cart_data' => 'O:32:"Darryldecode\\Cart\\CartCollection":1:{s:8:"' . "\0" . '*' . "\0" . 'items";a:1:{s:32:"429bc39777f3ad126e9845924a8d106a";O:32:"Darryldecode\\Cart\\ItemCollection":2:{s:9:"' . "\0" . '*' . "\0" . 'config";a:6:{s:14:"format_numbers";b:0;s:8:"decimals";i:0;s:9:"dec_point";s:1:".";s:13:"thousands_sep";s:1:",";s:7:"storage";s:21:"App\\Helpers\\DBStorage";s:6:"events";N;}s:8:"' . "\0" . '*' . "\0" . 'items";a:6:{s:2:"id";s:32:"429bc39777f3ad126e9845924a8d106a";s:4:"name";s:12:"Product Vest";s:5:"price";d:15;s:8:"quantity";s:1:"2";s:10:"attributes";O:41:"Darryldecode\\Cart\\ItemAttributeCollection":1:{s:8:"' . "\0" . '*' . "\0" . 'items";a:3:{s:14:"Primary colour";s:3:"Red";s:4:"Size";s:2:"XS";s:10:"listing_id";s:1:"1";}}s:10:"conditions";a:0:{}}}}}',
                'session_key' => NULL,
                'verified' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'listing_id' => NULL,
                'seller_id' => NULL,
                'user_id' => 2,
                'email' => 'olabayo96@yahoo.com',
                'last_name' => 'User',
                'status' => 'open',
                'amount' => '100000.00',
                'currency' => NULL,
                'reference' => 'YXpOkb3ERhSoOAOanCLKlbsrm',
                'listing_options' => NULL,
                'choices' => NULL,
                'customer_details' => NULL,
                'accepted_at' => NULL,
                'declined_at' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2019-01-25 07:06:39',
                'updated_at' => '2019-01-25 07:06:39',
                'cart_data' => 'O:32:"Darryldecode\\Cart\\CartCollection":1:{s:8:"' . "\0" . '*' . "\0" . 'items";a:1:{s:32:"657f96384337a554ee7c863a6e8102e6";O:32:"Darryldecode\\Cart\\ItemCollection":2:{s:9:"' . "\0" . '*' . "\0" . 'config";a:6:{s:14:"format_numbers";b:0;s:8:"decimals";i:0;s:9:"dec_point";s:1:".";s:13:"thousands_sep";s:1:",";s:7:"storage";s:21:"App\\Helpers\\DBStorage";s:6:"events";N;}s:8:"' . "\0" . '*' . "\0" . 'items";a:6:{s:2:"id";s:32:"657f96384337a554ee7c863a6e8102e6";s:4:"name";s:12:"Moto G5 Plus";s:5:"price";d:50000;s:8:"quantity";i:2;s:10:"attributes";O:41:"Darryldecode\\Cart\\ItemAttributeCollection":1:{s:8:"' . "\0" . '*' . "\0" . 'items";a:1:{s:10:"listing_id";s:1:"3";}}s:10:"conditions";a:0:{}}}}}',
                'session_key' => NULL,
                'verified' => 0,
            ),
        ));
        
        
    }
}