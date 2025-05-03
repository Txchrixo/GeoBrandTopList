<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

class BrandFactory extends Factory
{
    public function definition()
    {
        static $countries;
        static $brand_img_urls;
        
        if (!$countries) {
            $countries = Country::whereIn('country_code_cca2', ['MT', 'BG', 'FR', 'CM'])
                ->pluck('country_id')
                ->toArray();
        }

        if (!$brand_img_urls) {
            $brand_img_urls = [
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/cashed-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/vegasino-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/alexander-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/talismania-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/betalright-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/kingmaker-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/tikitaka-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/cazeus-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/wonaco-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/gransino-casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/Casombie-Casino.webp',
                'https://www.casinoonlinefrancais.info/cdn-cgi/image/format=webp,width=255,height=100,quality=80/img/logo250/LegendPlay-Casino.webp',
            ];
        }

        $brandName = $this->faker->unique()->company . ' Casino';
        $slug = strtolower(str_replace([' ', "'"], ['-', ''], $brandName));

        return [
            'brand_name' => $brandName,
            'brand_url' => "https://www.casinoonlinefrancais.info/casino/{$slug}/go.html",
            'brand_img_url' => $this->faker->randomElement($brand_img_urls),
            'brand_rating' => $this->faker->randomFloat(1, 4.0, 5.0),
            'brand_country_id' => $this->faker->randomElement($countries),
            'is_active' => $this->faker->boolean(95)
        ];
    }
}