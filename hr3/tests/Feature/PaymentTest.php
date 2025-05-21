<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $claim;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->claim = Claim::factory()->create([
            'status' => 'approved'
        ]);
    }

    public function test_can_get_pending_payments()
    {
        $this->actingAs($this->user);

        Payment::factory()->count(3)->create([
            'status' => 'pending'
        ]);

        $response = $this->getJson('/api/payments/pending');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'payments' => [
                    '*' => [
                        'id',
                        'claim_id',
                        'amount',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_can_get_payment_stats()
    {
        $this->actingAs($this->user);

        Payment::factory()->count(2)->create([
            'status' => 'pending'
        ]);

        Payment::factory()->create([
            'status' => 'processed',
            'processed_at' => now()
        ]);

        $response = $this->getJson('/api/payments/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'stats' => [
                    'pending_count',
                    'processed_today',
                    'amount_today',
                    'monthly_total'
                ]
            ]);
    }

    public function test_can_get_payment_details()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create();

        $response = $this->getJson("/api/payments/{$payment->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'payment' => [
                    'id',
                    'claim_id',
                    'amount',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_can_process_payment()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/process", [
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF123',
            'payment_date' => now()->format('Y-m-d'),
            'notes' => 'Payment processed'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Payment processed successfully'
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'processed',
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF123',
            'processed_by' => $this->user->id
        ]);

        $this->assertDatabaseHas('claims', [
            'id' => $payment->claim_id,
            'status' => 'paid'
        ]);
    }

    public function test_cannot_process_already_processed_payment()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create([
            'status' => 'processed'
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/process", [
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF123',
            'payment_date' => now()->format('Y-m-d'),
            'notes' => 'Payment processed'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'message' => 'Payment is not in pending status'
            ]);
    }

    public function test_validates_payment_processing_input()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/process", [
            'payment_method' => '',
            'reference_number' => '',
            'payment_date' => '',
            'notes' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'payment_method',
                    'reference_number',
                    'payment_date'
                ]
            ]);
    }
}
