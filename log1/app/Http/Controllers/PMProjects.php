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
        $projects = DB::table('projects')
            ->join('accounts', 'projects.team_leader_id', '=', 'accounts.id')
            ->select('projects.*', 'accounts.fullname as team_leader_name')
            ->get();
            
        $teams = DB::table('project_team')
            ->select('group')
            ->groupBy('group')
            ->get();

        $membersByGroup = DB::table('project_team')
            ->join('accounts', 'project_team.account_id', '=', 'accounts.id')
            ->select('project_team.group', 'accounts.id', 'accounts.fullname', 'accounts.role')
            ->get()
            ->groupBy('group');

        $tasksByProjectId = DB::table('tasks')
            ->select('id', 'title', 'status', 'project_id')
            ->get()
            ->groupBy('project_id');

            
        $leaders = DB::table('accounts')->where('role', 'TeamLeader')->get();
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
