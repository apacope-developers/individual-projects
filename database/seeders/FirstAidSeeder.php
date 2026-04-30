<?php

namespace Database\Seeders;

use App\Models\EmergencyContact;
use App\Models\KitItem;
use Illuminate\Database\Seeder;

class FirstAidSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default contacts (only if table is empty)
        if (EmergencyContact::count() === 0) {
            foreach (config('firstaid.default_contacts') as $contact) {
                EmergencyContact::create([
                    'name'      => $contact['name'],
                    'phone'     => $contact['phone'],
                    'type'      => $contact['type'],
                    'icon'      => $contact['icon'],
                    'is_default' => true,
                ]);
            }
        }

        // Seed default kit items (only if table is empty)
        if (KitItem::count() === 0) {
            foreach (config('firstaid.default_kit') as $item) {
                KitItem::create([
                    'name'     => $item['name'],
                    'category' => $item['category'],
                ]);
            }
        }
    }
}