<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $path = storage_path() . "/app.json";
        $json = json_decode(file_get_contents($path), true);
        $categories = [];

        try {
            foreach($json as $arr) {
                $grandparent = Category::create([
                    'name' => $arr['nama']
                ]);

                foreach($arr['sub'] as $arr2) {
                    $parent = Category::create([
                        'name' => $arr2['nama'],
                        'parent_id' => $grandparent->id
                    ]);

                    foreach ($arr2['sub'] as $i => $string) {
                        Category::create([
                            'name' => $string,
                            'parent_id' => $parent->id
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
