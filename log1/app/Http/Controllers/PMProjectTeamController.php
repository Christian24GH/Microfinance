<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PMProjectTeamController extends Controller
{
    public function index()
    {
        $viewdata = $this->init();
        $viewdata+=[
            'pageTitle'=>'Team Management',
            'hasBtn'=>'createTeamModal'
        ];

        $teams = DB::table('project_team')
            ->select('group')
            ->groupBy('group')
            ->get();

        $teamData = [];

        foreach ($teams as $team) {
            $members = DB::table('project_team')
                ->join('accounts as a', 'project_team.account_id', '=', 'a.id')
                ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
                ->join('roles as r', 'r.id', '=', 'a.role_id')
                ->where('project_team.group', $team->group)
                ->select(
                    DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname"),
                    'r.role',
                    'a.id'
                )
                ->get();

            $leader = $members->firstWhere('role', 'Team Leader');
            $memberNames = $members->pluck('fullname');

            $teamData[] = [
                'group' => $team->group,
                'leader' => $leader ? $leader->fullname : 'No Leader Assigned',
                'members' => $memberNames,
            ];
        }

        // Fetch all Employees (role: EMPLOYEE)
        $Employees = DB::table('accounts as a')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->join('roles as r', 'r.id', '=', 'a.role_id')
            ->where('r.role', 'EMPLOYEE')
            ->select(
                'a.id',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname")
            )
            ->get();

        return view('pm.teams.index', $viewdata)
            ->with('teams', $teamData)
            ->with('accounts',$Employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array',
            'leader' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $groupName = $request->name;
            $members = $request->members;
            $leaderId = $request->leader;

            // Ensure no duplicates while inserting members (leader might be in members)
            $uniqueMembers = collect($members)->unique()->values();

            // Get role IDs from roles table
            $teamMemberRoleId = DB::table('roles')->where('role', 'Team Member')->value('id');
            $teamLeaderRoleId = DB::table('roles')->where('role', 'Team Leader')->value('id');

            // Insert members to project_team
            foreach ($uniqueMembers as $accountId) {
                DB::table('project_team')->insert([
                    'group' => $groupName,
                    'account_id' => $accountId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('accounts')->where('id', $accountId)->update([
                    'role_id' => $teamMemberRoleId
                ]);
            }

            // Check if leader is already in the group
            $leaderExists = DB::table('project_team')
                ->where('group', $groupName)
                ->where('account_id', $leaderId)
                ->exists();

            if (!$leaderExists) {
                // Insert leader if not already in group
                DB::table('project_team')->insert([
                    'group' => $groupName,
                    'account_id' => $leaderId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Assign leader role
            DB::table('accounts')->where('id', $leaderId)->update([
                'role_id' => $teamLeaderRoleId
            ]);
        });

        return back()->with('success', 'Team created successfully.');
    }

    public function destroy($group)
    {
        DB::transaction(function () use ($group) {
            // Get account IDs in this team
            // Get Employee role_id from roles table
            $employeeRoleId = DB::table('roles')->where('role', 'Employee')->value('id');

            // Get all account IDs in the group
            $accountIds = DB::table('project_team')
                ->where('group', $group)
                ->pluck('account_id');

            // Reset their roles to Employee
            DB::table('accounts')
                ->whereIn('id', $accountIds)
                ->update(['role_id' => $employeeRoleId]);

            // Remove them from project_team
            DB::table('project_team')->where('group', $group)->delete();
        });

        return back()->with('success', 'Team deleted successfully and roles reset.');
    }
}
