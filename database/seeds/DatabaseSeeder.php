<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $toTruncate = ['goods'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }
        // $this->call(UsersTableSeeder::class);
         $this->call(GoodsTableSeeder::class);
    }
}
