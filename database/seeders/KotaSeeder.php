<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Kota;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeders/data/kota.csv'), 'r');
        $getData = fgetcsv($csv, 2000, ",");

        DB::beginTransaction();
        try{
            $firstline = true;
            while (($data = fgetcsv($csv, 2000, ",")) !== FALSE) {
                    Kota::create([
                        'id'        => $data[0],
                        'provinsi_id'=> $data[1],
                        'kota'      => $data[2],
                    ]);
                $firstline = false;
            }
            DB::commit();
            fclose($csv);

        }catch(Exception $e){
            DB::rollBack();
            echo $e->getMessage();
        }
    }
}
