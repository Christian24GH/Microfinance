<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\Timesheet;
use App\Models\TimeEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TimesheetApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed departments, positions, employees, timesheets, and time entries
        $this->seed();
    }

    public function test_approve_timesheet_successfully()
    {
        $user = User::first();
        $timesheet = Timesheet::where('status', 'pending')->first();
        $this->actingAs($user, 'sanctum');
        Event::fake();

        $response = $this->postJson("/api/timesheets/{$timesheet->id}/approve");
        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'status' => 'approved',
            'approved_by' => $user->id
        ]);
    }

    public function test_reject_timesheet_successfully()
    {
        $user = User::first();
        $timesheet = Timesheet::where('status', 'pending')->first();
        $this->actingAs($user, 'sanctum');
        Event::fake();

        $response = $this->postJson("/api/timesheets/{$timesheet->id}/reject", [
            'reason' => 'Test rejection reason'
        ]);
        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'status' => 'rejected',
            'rejection_reason' => 'Test rejection reason',
            'rejected_by' => $user->id
        ]);
    }

    public function test_approve_timesheet_validation()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/timesheets/9999/approve');
        $response->assertStatus(404);
    }

    public function test_reject_timesheet_validation()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/timesheets/9999/reject', ['reason' => 'No such timesheet']);
        $response->assertStatus(404);
    }
}
