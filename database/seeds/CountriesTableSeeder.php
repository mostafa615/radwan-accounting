<?php

use Illuminate\Database\Seeder;

use App\Models\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $countries  =[
            'الإسكندرية',
            'الإسماعيلية',
            'أسوان',
            'أسيوط',
            'الأقصر',
            'البحر الأحمر',
            'البحيرة',
            'بني سويف',
            'بورسعيد',
            'جنوب سيناء	',
            'الجيزة',
            'الدقهلية',
            'دمياط',
            'سوهاج',
            'السويس',
            'الشرقية',
            'شمال سيناء',
            'الغربية',
            'الفيوم',
            'القاهرة',
            'القليوبية',
            'قنا',
            'كفر الشيخ',
            'مطروح',
            'المنوفية',
            'المنيا',
            'الوادي الجديد'
        ];
        // Country::truncate();
        foreach($countries as $country)
            Country::create(['name'=>$country]);
    }
}
