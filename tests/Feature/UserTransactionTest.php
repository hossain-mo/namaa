<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTransactionTest extends TestCase
{
    /** @test */
    public function it_fetches_data_by_provider()
    {
        $response = $this->getJson('/api/v1/users?provider=DataProviderX');
        $response->assertStatus(200)
                    ->assertJsonStructure([
                        '*' => ['amount', 'currency', 'email', 'status', 'date', 'id']
                    ]);
    }

    /** @test */
    public function it_fetches_data_by_status_code()
    {
        $response = $this->getJson('/api/v1/users?statusCode=authorised');

        $response->assertStatus(200)
                    ->assertJsonFragment(['status' => 'authorised']);
    }

    /** @test */
    public function it_fetches_data_within_balance_range()
    {
        $response = $this->getJson('/api/v1/users?balanceMin=100&balanceMax=300');

        $this->assertIsArray($response->json());

        // Check that the amount is within the specified range
        foreach ($response->json() as $item) { // Directly access the response as it's an array
            $this->assertGreaterThanOrEqual(100, $item['amount']);
            $this->assertLessThanOrEqual(300, $item['amount']);
        }
    }

    /** @test */
    public function it_fetches_data_by_currency()
    {
        $response = $this->getJson('/api/v1/users?currency=EUR');

        $response->assertStatus(200)
                    ->assertJsonFragment(['currency' => 'EUR']);
    }

    /** @test */
    public function it_fetches_data_by_combined_filters()
    {
        $response = $this->getJson('/api/v1/users?provider=DataProviderX&statusCode=authorised&balanceMin=100&balanceMax=300&currency=EUR');

        $this->assertIsArray($response->json());

        foreach ($response->json() as $item) { // Directly access the response as it's an array
            $this->assertGreaterThanOrEqual(100, $item['amount']);
            $this->assertLessThanOrEqual(300, $item['amount']);
            $this->assertEquals('authorised', $item['status']);
            $this->assertEquals('EUR', $item['currency']);
        }
    }

    /** @test */
    public function it_returns_422_when_statusCode_invalid()
    {
        $response = $this->getJson('/api/v1/users?provider=DataProviderX&statusCode=unknownStatus');

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['statusCode']);
    }

    /** @test */
    public function it_returns_422_when_invalid_provider_is_passed()
    {
        $response = $this->getJson('/api/v1/users?provider=InvalidProvider');

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['provider']);
    }

    /** @test */
    public function it_returns_422_when_balanceMin_is_greater_than_balanceMax()
    {
        $response = $this->getJson('/api/v1/users?balanceMin=300&balanceMax=100');

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['balanceMax']);
    }

    /** @test */
    public function it_returns_422_when_bcurrency_invalid()
    {
        $response = $this->getJson('/api/v1/users?currency=somethingwrong');

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['currency']);
    }
}
