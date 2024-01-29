<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kecamatan;
use Exception;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeders/data/kecamatan.csv'), 'r');
        $getData = fgetcsv($csv, 2000, ",");

        DB::beginTransaction();
        try{
            $firstline = true;
            while (($data = fgetcsv($csv, 2000, ",")) !== FALSE) {
                    Kecamatan::create([
                        'id'        => $data[0],
                        'kota_id'   => $data[1],
                        'kecamatan'      => $data[2],
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
