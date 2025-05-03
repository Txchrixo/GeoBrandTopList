<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class CountrySeeder extends Seeder
{
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 2; // seconds

    public function run()
    {
        Country::truncate();
        
        $retryCount = 0;
        $success = false;

        while ($retryCount < self::MAX_RETRIES && !$success) {
            try {
                $response = Http::timeout(30)
                    ->retry(3, 1000)
                    ->withoutVerifying()
                    ->get('https://restcountries.com/v3.1/all');

                if ($response->successful()) {
                    $countries = $response->json();

                    usort($countries, function ($a, $b) {
                        return strcmp($a['name']['common'], $b['name']['common']);
                    });

                    foreach ($countries as $countryData) {
                        if (!isset($countryData['cca2'], $countryData['name']['common'])) continue;

                        Country::updateOrCreate(
                            ['country_code_cca2' => $countryData['cca2']],
                            [
                                'country_name' => $countryData['name']['common'],
                                'country_flag_url' => $countryData['flags']['png'] ?? null,
                            ]
                        );
                    }

                    $success = true;
                    $this->command->info('Countries seeded successfully!');
                    
                } else {
                    $this->command->error("Failed to fetch countries. Status: {$response->status()}");
                    $retryCount++;
                    if ($retryCount < self::MAX_RETRIES) {
                        sleep(self::RETRY_DELAY);
                    }
                }

            } catch (RequestException $e) {
                Log::error('Country seeder error: ' . $e->getMessage());
                $this->command->error("Attempt " . ($retryCount + 1) . " failed: " . $e->getMessage());
                $retryCount++;
                if ($retryCount < self::MAX_RETRIES) {
                    sleep(self::RETRY_DELAY);
                }
            }
        }

        if (!$success) {
            $this->command->error('Failed to seed countries after ' . self::MAX_RETRIES . ' attempts');
            // Ajouter des pays par défaut en cas d'échec
            $this->seedDefaultCountries();
        }
    }

    private function seedDefaultCountries()
    {
        $defaultCountries = [
            ['country_code_cca2' => 'FR', 'country_name' => 'France'],
            ['country_code_cca2' => 'US', 'country_name' => 'United States'],
            ['country_code_cca2' => 'GB', 'country_name' => 'United Kingdom'],
            ['country_code_cca2' => 'DE', 'country_name' => 'Germany'],
            ['country_code_cca2' => 'ES', 'country_name' => 'Spain'],
            ['country_code_cca2' => 'BG', 'country_name' => 'Bulgaria'],
            ['country_code_cca2' => 'CM', 'country_name' => 'Cameroon'],
            ['country_code_cca2' => 'MT', 'country_name' => 'Malta'],
        ];

        foreach ($defaultCountries as $country) {
            Country::updateOrCreate(
                ['country_code_cca2' => $country['country_code_cca2']],
                $country
            );
        }

        $this->command->info('Default countries seeded successfully!');
    }
} 