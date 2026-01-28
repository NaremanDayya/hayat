<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\HealthCondition;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            [
                'husband_name' => 'أحمد محمد علي',
                'husband_id_number' => '402345678',
                'husband_dob' => '1985-05-12',
                'husband_phone' => '0599123456',
                'marital_status' => 'متزوج',
                'wife_name' => 'سارة محمود حسن',
                'wife_id_number' => '402987654',
                'wife_dob' => '1990-08-20',
                'wife_phone' => '0599654321',
                'original_address' => 'غزة - حي الشجاعية',
                'current_address' => 'مخيم النويري - خيمة رقم 45',
                'sons' => [
                    ['name' => 'يوسف أحمد علي', 'id_number' => '405112233', 'dob' => '2010-01-01'],
                    ['name' => 'عمر أحمد علي', 'id_number' => '405445566', 'dob' => '2015-03-15'],
                ],
                'daughters' => [
                    ['name' => 'مريم أحمد علي', 'id_number' => '405778899', 'dob' => '2012-06-10'],
                ],
                'health' => [
                    ['person_name' => 'أحمد محمد علي', 'details' => 'إصابة في القدم اليمنى شظايا'],
                ]
            ],
            [
                'husband_name' => 'محمود إبراهيم خليل',
                'husband_id_number' => '403112233',
                'husband_dob' => '1978-02-25',
                'husband_phone' => '0597111222',
                'marital_status' => 'أرمل',
                'wife_name' => 'فاطمة الزهراء علي',
                'wife_id_number' => '403445566',
                'wife_dob' => '1982-11-05',
                'wife_phone' => '0597333444',
                'original_address' => 'خانيونس - وسط البلد',
                'current_address' => 'مخيم النويري - خيمة رقم 12',
                'sons' => [
                    ['name' => 'خليل محمود خليل', 'id_number' => '406111222', 'dob' => '2005-09-12'],
                ],
                'daughters' => [
                    ['name' => 'ليلى محمود خليل', 'id_number' => '406333444', 'dob' => '2008-04-30'],
                    ['name' => 'هدى محمود خليل', 'id_number' => '406555666', 'dob' => '2013-12-25'],
                ],
                'health' => [
                    ['person_name' => 'فاطمة الزهراء علي', 'details' => 'مرض السكري المزمن'],
                    ['person_name' => 'خليل محمود خليل', 'details' => 'إعاقة حركية بسيطة'],
                ]
            ],
            [
                'husband_name' => 'ياسين جابر القواسمي',
                'husband_id_number' => '401556677',
                'husband_dob' => '1992-10-10',
                'husband_phone' => '0569888777',
                'marital_status' => 'مطلق',
                'wife_name' => 'منى كمال رضوان',
                'wife_id_number' => '401990011',
                'wife_dob' => '1995-12-12',
                'wife_phone' => '0569111222',
                'original_address' => 'جباليا - المعسكر',
                'current_address' => 'مخيم النويري - خيمة رقم 88',
                'sons' => [],
                'daughters' => [
                    ['name' => 'أمل ياسين القواسمي', 'id_number' => '407111222', 'dob' => '2020-05-05'],
                ],
                'health' => []
            ]
        ];

        foreach ($families as $data) {
            $family = Family::create([
                'husband_name' => $data['husband_name'],
                'husband_id_number' => $data['husband_id_number'],
                'husband_dob' => $data['husband_dob'],
                'husband_phone' => $data['husband_phone'],
                'marital_status' => $data['marital_status'],
                'wife_name' => $data['wife_name'],
                'wife_id_number' => $data['wife_id_number'],
                'wife_dob' => $data['wife_dob'],
                'wife_phone' => $data['wife_phone'],
                'family_members_count' => 2 + count($data['sons']) + count($data['daughters']),
                'original_address' => $data['original_address'],
                'current_address' => $data['current_address'],
            ]);

            foreach ($data['sons'] as $son) {
                FamilyMember::create(array_merge($son, ['family_id' => $family->id, 'gender' => 'male']));
            }

            foreach ($data['daughters'] as $daughter) {
                FamilyMember::create(array_merge($daughter, ['family_id' => $family->id, 'gender' => 'female']));
            }

            foreach ($data['health'] as $h) {
                HealthCondition::create([
                    'family_id' => $family->id,
                    'person_name' => $h['person_name'],
                    'condition_details' => $h['details'],
                ]);
            }
        }
    }
}
