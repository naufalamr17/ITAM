<?php

namespace Tests\Unit;

use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AzureSocialiteTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_azure_driver_is_registered()
    {
        // Assert that the azure config exists
        $this->assertNotEmpty(config('services.azure.client_id'), 'Azure Client ID is not set.');
        $this->assertNotEmpty(config('services.azure.client_secret'), 'Azure Client Secret is not set.');
        $this->assertNotEmpty(config('services.azure.redirect'), 'Azure Redirect URI is not set.');
        $this->assertNotEmpty(config('services.azure.tenant'), 'Azure Tenant ID is not set.');

        // Attempt to call the driver
        try {
            $driver = Socialite::driver('azure');
            $this->assertNotNull($driver, 'Azure driver should be instantiable.');
        } catch (\Exception $e) {
            $this->fail('Failed to instantiate Azure driver: ' . $e->getMessage());
        }
    }
}
