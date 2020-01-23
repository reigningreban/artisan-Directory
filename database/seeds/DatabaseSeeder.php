<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $artisans=DB::table('artisans')
        ->get();
        foreach ($artisans as $artisan ) {
            DB::table('offered_by')->insert([
                'artisan_id'=>$artisan->id,
                'service_id'=>rand(1,8),
            ]);
        }
    }
}
