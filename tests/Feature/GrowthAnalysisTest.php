<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Child;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrowthAnalysisTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user and token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /** @test */
    public function user_can_add_child()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/children', [
            'name' => 'Test Child',
            'gender' => 'male',
            'birth_date' => '2022-01-15',
            'birth_weight' => 3.2,
            'birth_height' => 48.5,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Anak berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('children', [
            'name' => 'Test Child',
            'gender' => 'male',
        ]);
    }

    /** @test */
    public function user_can_add_growth_record_and_get_ai_analysis()
    {
        // Create a child first
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
            'birth_date' => now()->subMonths(24), // 24 months old
        ]);

        // Add growth record
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/children/{$child->id}/growth", [
            'measurement_date' => now()->format('Y-m-d'),
            'weight' => 12.0,
            'height' => 85.0,
            'head_circumference' => 48.0,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'record',
                    'analysis' => [
                        'z_scores' => [
                            'height_for_age',
                            'weight_for_age',
                            'weight_for_height',
                        ],
                        'status' => [
                            'stunting',
                            'wasting',
                            'underweight',
                        ],
                        'detailed_analysis',
                        'recommendations',
                    ],
                ],
            ]);

        // Verify data is saved
        $this->assertDatabaseHas('growth_records', [
            'child_id' => $child->id,
            'weight' => 12.0,
            'height' => 85.0,
        ]);
    }

    /** @test */
    public function ai_correctly_detects_normal_growth()
    {
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
            'gender' => 'male',
            'birth_date' => now()->subMonths(24),
        ]);

        // Normal measurements for 24-month-old boy
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/children/{$child->id}/growth", [
            'measurement_date' => now()->format('Y-m-d'),
            'weight' => 12.5,
            'height' => 87.0,
        ]);

        $response->assertStatus(201);

        $data = $response->json('data.analysis');

        // Z-scores should be near 0 for normal growth
        $this->assertGreaterThan(-2, $data['z_scores']['height_for_age']);
        $this->assertEquals('normal', $data['status']['stunting']);
    }

    /** @test */
    public function ai_correctly_detects_stunting()
    {
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
            'gender' => 'male',
            'birth_date' => now()->subMonths(24),
        ]);

        // Short height for age (stunted)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/children/{$child->id}/growth", [
            'measurement_date' => now()->format('Y-m-d'),
            'weight' => 10.0,
            'height' => 78.0, // Very short for 24 months
        ]);

        $response->assertStatus(201);

        $data = $response->json('data.analysis');

        // Should detect stunting
        $this->assertLessThan(-2, $data['z_scores']['height_for_age']);
        $this->assertContains($data['status']['stunting'], ['at_risk', 'stunted', 'severely_stunted']);
        $this->assertTrue($data['needs_intervention']);
    }

    /** @test */
    public function user_can_get_growth_trend()
    {
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Create multiple growth records
        for ($i = 0; $i < 5; $i++) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->postJson("/api/children/{$child->id}/growth", [
                'measurement_date' => now()->subMonths($i)->format('Y-m-d'),
                'weight' => 10 + ($i * 0.5),
                'height' => 80 + ($i * 2),
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/children/{$child->id}/growth/trend?months=6");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'trend_data',
                    'trend_analysis',
                ],
            ]);
    }

    /** @test */
    public function user_cannot_access_other_users_children()
    {
        $otherUser = User::factory()->create();
        $otherChild = Child::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/children/{$otherChild->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function validation_works_for_invalid_measurements()
    {
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Invalid weight (too high)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/children/{$child->id}/growth", [
            'measurement_date' => now()->format('Y-m-d'),
            'weight' => 100, // Invalid
            'height' => 85,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['weight']);
    }
}
