<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $employee;
    protected $manager;
    protected $leaveType;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->employee = Employee::factory()->create();
        $this->manager = Employee::factory()->create(['is_manager' => true]);
        $this->leaveType = LeaveType::factory()->create();
    }

    public function test_employee_can_view_their_leave_requests()
    {
        $user = User::factory()->create(['employee_id' => $this->employee->id]);
        $leaveRequests = LeaveRequest::factory()->count(3)->create([
            'employee_id' => $this->employee->id
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/leave-requests');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'leave_requests' => [
                    '*' => [
                        'id',
                        'employee_id',
                        'leave_type_id',
                        'start_date',
                        'end_date',
                        'total_days',
                        'status',
                        'reason'
                    ]
                ]
            ]);
    }

    public function test_employee_can_create_leave_request()
    {
        $user = User::factory()->create(['employee_id' => $this->employee->id]);
        $data = [
            'leave_type_id' => $this->leaveType->id,
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(7)->format('Y-m-d'),
            'reason' => 'Family vacation'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/leave-requests', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'leave_request' => [
                    'id',
                    'employee_id',
                    'leave_type_id',
                    'start_date',
                    'end_date',
                    'total_days',
                    'status',
                    'reason'
                ]
            ]);

        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'reason' => 'Family vacation'
        ]);
    }

    public function test_manager_can_approve_leave_request()
    {
        $user = User::factory()->create(['employee_id' => $this->manager->id]);
        $leaveRequest = LeaveRequest::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/leave-requests/{$leaveRequest->id}/approve");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Leave request approved successfully'
            ]);

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'approved',
            'approved_by' => $this->manager->id
        ]);
    }

    public function test_manager_can_reject_leave_request()
    {
        $user = User::factory()->create(['employee_id' => $this->manager->id]);
        $leaveRequest = LeaveRequest::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/leave-requests/{$leaveRequest->id}/reject", [
                'rejection_reason' => 'Insufficient staff coverage'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Leave request rejected successfully'
            ]);

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'rejected',
            'rejection_reason' => 'Insufficient staff coverage'
        ]);
    }

    public function test_employee_can_cancel_pending_leave_request()
    {
        $user = User::factory()->create(['employee_id' => $this->employee->id]);
        $leaveRequest = LeaveRequest::factory()->create([
            'employee_id' => $this->employee->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/leave-requests/{$leaveRequest->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Leave request cancelled successfully'
            ]);

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_employee_cannot_exceed_leave_balance()
    {
        $user = User::factory()->create(['employee_id' => $this->employee->id]);
        $leaveType = LeaveType::factory()->create(['default_days' => 20]);

        // Create an approved leave request that uses up most of the balance
        LeaveRequest::factory()->create([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $leaveType->id,
            'total_days' => 18,
            'status' => 'approved'
        ]);

        // Try to create another leave request that would exceed the balance
        $data = [
            'leave_type_id' => $leaveType->id,
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(10)->format('Y-m-d'),
            'reason' => 'Additional leave'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/leave-requests', $data);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Insufficient leave days available'
            ]);
    }
}
