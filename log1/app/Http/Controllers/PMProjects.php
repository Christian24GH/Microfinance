<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PMProjects extends Controller
{
    public function index()
    {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Projects',
            'hasBtn' => 'createProjectModal',
        ];
        // Projects with Team Leader names
        $projects = DB::table('projects')
            ->join('accounts as a', 'projects.team_leader_id', '=', 'a.id')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->select('projects.*', DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as team_leader_name"))
            ->get();

        // Groups in Project Teams
        $teams = DB::table('project_team')
            ->select('group')
            ->groupBy('group')
            ->get();

        // Members by Group with fullname and role
        $membersByGroup = DB::table('project_team')
            ->join('accounts as a', 'project_team.account_id', '=', 'a.id')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->join('roles as r', 'r.id', '=', 'a.role_id')
            ->select(
                'project_team.group',
                'a.id',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname"),
                'r.role'
            )
            ->get()
            ->groupBy('group');

        // Tasks grouped by Project ID
        $tasksByProjectId = DB::table('tasks')
            ->select('id', 'title', 'status', 'project_id')
            ->get()
            ->groupBy('project_id');

        // Leaders (TeamLeader role accounts)
        $leaders = DB::table('accounts as a')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->join('roles as r', 'r.id', '=', 'a.role_id')
            ->where('r.role', 'Team Leader')
            ->get([
                'a.id',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname")
            ]);
        return view('pm.projects.index', $viewdata)
            ->with('projects', $projects)
            ->with('teams', $teams)
            ->with('membersByGroup', $membersByGroup)
            ->with('tasksByProjectId', $tasksByProjectId)
            ->with('leaders', $leaders);
    }

    // Store a new project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'description' => 'nullable|string|min:1',
            'budget' => 'required|numeric',
            'end_date' => 'required|date|after_or_equal:start_date',
            'team_leader_id' => 'required|exists:accounts,id',
        ]);

        DB::table('projects')->insert([
            'project_code' => 'Project-' . time(),
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget' => $request->budget,
            'team_leader_id' => $request->team_leader_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pm.projects.index')->with('success', 'Project added successfully!');
    }


    /* Show project details
    public function show($id)
    {
        $project = DB::table('projects')
            ->where('id', $id)
            ->first();

        $teamMembers = DB::table('project_team')
            ->join('accounts', 'project_team.account_id', '=', 'accounts.id')
            ->where('project_team.project_id', $id)
            ->select('accounts.*', 'project_team.group')
            ->get();

        $tasks = DB::table('tasks')
            ->where('project_id', $id)
            ->get();

        return view('projects.show', compact('project', 'teamMembers', 'tasks'));
    }*/

    // Update a project
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'team_leader_id' => 'required|exists:accounts,id',
        ]);

        DB::table('projects')->where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'team_leader_id' => $request->team_leader_id,
            'updated_at' => now(),
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    // Delete a project
    public function destroy($id)
    {
        DB::table('projects')->where('id', $id)->delete();
        return redirect()->route('pm.projects.index')->with('success', 'Project deleted successfully!');
    }

    
}
