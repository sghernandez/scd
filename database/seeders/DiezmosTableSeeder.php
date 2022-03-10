<?php

namespace Database\Seeders;
use App\Models\Diezmo;

use Illuminate\Database\Seeder;

class DiezmosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 1000; $i++)
        {
            \App\Models\Diezmo::factory()->create([
                'diezmo' => '1000',
                'ofrenda' => '5000', 
                'user_id' => 1,
            ]);
        }
      
    }
}
