<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class onesearchArtisanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // $faker = Faker::create();
    	// for($i=0;$i<=100;$i++) {
	    //     DB::table('artisans')->insert([
        //         'companyname'=>$faker->company,
        //         'firstname' => $faker->firstNameMale,
        //         'lastname'=>$faker->lastName,
	    //         'email' => $faker->email,
        //         'password' => Hash::make('secret'),
        //         'address'=>$faker->address,
        //         'city_id'=>rand(1,750),
        //         'phone_no'=>rand(10000000000,99999999999),
        //         'description'=>$faker->paragraph($nbSentences = 2, $variableNbSentences = true),
        //         'registered'=>strtotime('now'),
        //     ]);
        // }

        $artisans=DB::table('artisans')
        ->get();
        foreach ($artisans as $artisan ) {
            DB::table('artisans')
            ->where('id',$artisan->id)
            ->update([
                'slog'=>str::random(10)
            ]);
        }
    }
}
