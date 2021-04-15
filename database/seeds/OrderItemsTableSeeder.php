<?php

use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_items')->delete();
        
        \DB::table('order_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'listing_id' => 1,
                'seller_id' => 2,
                'price' => '15.00',
                'quantity' => 2,
                'currency' => NULL,
                'listing_options' => NULL,
                'choices' => '{"Primary colour":"Red","Size":"XS","listing_id":"1"}',
                'deleted_at' => NULL,
                'created_at' => '2019-01-15 13:37:19',
                'updated_at' => '2019-01-15 13:37:19',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 2,
                'listing_id' => 3,
                'seller_id' => 2,
                'price' => '50000.00',
                'quantity' => 2,
                'currency' => NULL,
                'listing_options' => NULL,
                'choices' => '{"listing_id":"3"}',
                'deleted_at' => NULL,
                'created_at' => '2019-01-25 07:06:39',
                'updated_at' => '2019-01-25 07:06:39',
            ),
        ));
        
        
    }
}