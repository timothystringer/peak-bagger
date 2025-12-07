<?php

namespace Database\Seeders;

use App\Models\Peak;
use Illuminate\Database\Seeder;

class WainwrightsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/wainwrights.json');

        if (! file_exists($path)) {
            $this->command->info('No wainwrights dataset found at '.$path.'. Skipping.');

            return;
        }

        $data = json_decode(file_get_contents($path), true);

        foreach ($data as $item) {
            Peak::firstOrCreate([
                'name' => $item['name'],
            ], [
                'category' => $item['category'] ?? 'Wainwright',
                'lat' => $item['lat'] ?? 0,
                'lon' => $item['lon'] ?? 0,
                'elevation' => $item['elevation'] ?? null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        $this->command->info('Wainwrights seeder complete.');
    }
}
