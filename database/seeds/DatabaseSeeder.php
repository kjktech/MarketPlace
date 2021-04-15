<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(UsersProfilesTableSeeder::class);
       $this->call(RolesAndPermissionsSeeder::class);
       $this->call(ListingsTableSeeder::class);
       $this->call(CategoriesTableSeeder::class);
       $this->call(PricingModelsTableSeeder::class);
       $this->call(FiltersTableSeeder::class);
       $this->call(CategoryPricingModelTableSeeder::class);
       $this->call(DirectoriesTableSeeder::class);
       $this->call(DirectoryCategoriesTableSeeder::class);
       $this->call(StoresTableSeeder::class);
       $this->call(StoreCategoriesTableSeeder::class);
       $this->call(OrdersTableSeeder::class);
       $this->call(OrderItemsTableSeeder::class);
       $this->call(BanksTableSeeder::class);
    }
}
