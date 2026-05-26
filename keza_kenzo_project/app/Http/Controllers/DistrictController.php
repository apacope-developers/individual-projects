<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = Cache::get('rwanda_districts', function () {
            return $this->getDefaultDistricts();
        });

        $total_districts = count($districts);
        $total_pharmacies = 0;
        foreach ($districts as $district) {
            $total_pharmacies += count($district['pharmacies']);
        }

        $districts = array_map(function($district) {
            $district = (object) $district;
            $district->pharmacies = array_map(function($pharmacy) {
                return (object) $pharmacy;
            }, $district->pharmacies);
            return $district;
        }, $districts);

        return view('districts.index', compact('districts', 'total_districts', 'total_pharmacies'));
    }

    public function show($districtName)
    {
        $districts = Cache::get('rwanda_districts', function () {
            return $this->getDefaultDistricts();
        });

        $district = collect($districts)->firstWhere('name', ucfirst($districtName));

        if (!$district) {
            abort(404, 'District not found');
        }

        $district = (object) $district;
        $district->pharmacies = array_map(function($pharmacy) {
            return (object) $pharmacy;
        }, $district->pharmacies);

        return view('districts.show', compact('district'));
    }

    private function getDefaultDistricts()
    {
        return [
            [
                'name' => 'Kigali',
                'province' => 'Kigali',
                'pharmacies' => [
                    ['name' => 'Kigali Central Pharmacy', 'location' => 'Kigali City', 'phone' => '+250788123456'],
                    ['name' => 'Remera Medical Center', 'location' => 'Remera, Kigali', 'phone' => '+250788123457'],
                    ['name' => 'Kimironko Pharma', 'location' => 'Kimironko, Kigali', 'phone' => '+250788123458'],
                    ['name' => 'Gisozi Drug Store', 'location' => 'Gisozi, Kigali', 'phone' => '+250788123459'],
                    ['name' => 'Nyabugogo Pharmacy', 'location' => 'Nyabugogo, Kigali', 'phone' => '+250788123460'],
                ]
            ],
            [
                'name' => 'Nyarugenge',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Nyarugenge District Pharmacy', 'location' => 'Nyarugenge Town', 'phone' => '+250787123456'],
                    ['name' => 'Rulindo Health Center', 'location' => 'Rulindo, Nyarugenge', 'phone' => '+250787123457'],
                ]
            ],
            [
                'name' => 'Gicumbi',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Gicumbi District Hospital Pharmacy', 'location' => 'Gicumbi Town', 'phone' => '+250787123458'],
                ]
            ],
            [
                'name' => 'Rulindo',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Rulindo District Pharmacy', 'location' => 'Rulindo Town', 'phone' => '+250787123459'],
                ]
            ],
            [
                'name' => 'Musanze',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Musanze District Pharmacy', 'location' => 'Musanze Town', 'phone' => '+250787123460'],
                ]
            ],
            [
                'name' => 'Burera',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Burera District Pharmacy', 'location' => 'Burera Town', 'phone' => '+250787123461'],
                ]
            ],
            [
                'name' => 'Gakenke',
                'province' => 'Southern',
                'pharmacies' => [
                    ['name' => 'Gakenke District Pharmacy', 'location' => 'Gakenke Town', 'phone' => '+250792123456'],
                ]
            ],
            [
                'name' => 'Nyanza',
                'province' => 'Southern',
                'pharmacies' => [
                    ['name' => 'Nyanza District Pharmacy', 'location' => 'Nyanza Town', 'phone' => '+250792123457'],
                ]
            ],
            [
                'name' => 'Huye',
                'province' => 'Southern',
                'pharmacies' => [
                    ['name' => 'Huye District Pharmacy', 'location' => 'Huye Town', 'phone' => '+250792123458'],
                ]
            ],
            [
                'name' => 'Muhanga',
                'province' => 'Southern',
                'pharmacies' => [
                    ['name' => 'Muhanga District Pharmacy', 'location' => 'Muhanga Town', 'phone' => '+250792123459'],
                ]
            ],
            [
                'name' => 'Ruhango',
                'province' => 'Western',
                'pharmacies' => [
                    ['name' => 'Ruhango District Pharmacy', 'location' => 'Ruhango Town', 'phone' => '+250790123456'],
                ]
            ],
            [
                'name' => 'Ngororero',
                'province' => 'Western',
                'pharmacies' => [
                    ['name' => 'Ngororero District Pharmacy', 'location' => 'Ngororero Town', 'phone' => '+250790123457'],
                ]
            ],
            [
                'name' => 'Karongi',
                'province' => 'Western',
                'pharmacies' => [
                    ['name' => 'Karongi District Pharmacy', 'location' => 'Karongi Town', 'phone' => '+250790123458'],
                ]
            ],
            [
                'name' => 'Nyabihu',
                'province' => 'Western',
                'pharmacies' => [
                    ['name' => 'Nyabihu District Pharmacy', 'location' => 'Nyabihu Town', 'phone' => '+250790123459'],
                ]
            ],
            [
                'name' => 'Rusizi',
                'province' => 'Western',
                'pharmacies' => [
                    ['name' => 'Rusizi District Pharmacy', 'location' => 'Rusizi Town', 'phone' => '+250790123460'],
                ]
            ],
            [
                'name' => 'Nyamasheke',
                'province' => 'Southern',
                'pharmacies' => [
                    ['name' => 'Nyamasheke District Pharmacy', 'location' => 'Nyamasheke Town', 'phone' => '+250792123461'],
                ]
            ],
            [
                'name' => 'Ruhengeri',
                'province' => 'Northern',
                'pharmacies' => [
                    ['name' => 'Ruhengeri District Pharmacy', 'location' => 'Ruhengeri Town', 'phone' => '+250787123462'],
                ]
            ],
            [
                'name' => 'Kayonza',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Kayonza District Pharmacy', 'location' => 'Kayonza Town', 'phone' => '+250791123456'],
                ]
            ],
            [
                'name' => 'Kirehe',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Kirehe District Pharmacy', 'location' => 'Kirehe Town', 'phone' => '+250791123457'],
                ]
            ],
            [
                'name' => 'Ngoma',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Ngoma District Pharmacy', 'location' => 'Ngoma Town', 'phone' => '+250791123458'],
                ]
            ],
            [
                'name' => 'Rwamagana',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Rwamagana District Pharmacy', 'location' => 'Rwamagana Town', 'phone' => '+250791123459'],
                ]
            ],
            [
                'name' => 'Nyagatare',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Nyagatare District Pharmacy', 'location' => 'Nyagatare Town', 'phone' => '+250791123460'],
                ]
            ],
            [
                'name' => 'Gatsibo',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Gatsibo District Pharmacy', 'location' => 'Gatsibo Town', 'phone' => '+250791123461'],
                ]
            ],
            [
                'name' => 'Bugesera',
                'province' => 'Eastern',
                'pharmacies' => [
                    ['name' => 'Bugesera District Pharmacy', 'location' => 'Bugesera Town', 'phone' => '+250791123462'],
                ]
            ]
        ];
    }
}
