<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Timesheet;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TimesheetApprovalBrowserTest extends DuskTestCase
{
    public function test_can_approve_timesheet_in_ui()
    {
        $user = User::first();
        $timesheet = Timesheet::where('status', 'pending')->first();

        $this->browse(function (Browser $browser) use ($user, $timesheet) {
            $browser->loginAs($user)
                ->visit('/timesheet/approval')
                ->waitForText('Timesheet Approval')
                ->assertSee($timesheet->employee->name)
                ->click('@view-timesheet-' . $timesheet->id)
                ->waitFor('@approve-btn')
                ->click('@approve-btn')
                ->waitForText('Timesheet approved successfully')
                ->assertSee('Timesheet approved successfully');
        });
    }
}
