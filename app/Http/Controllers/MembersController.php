<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Member;
use App\TeamMember;
use App\User;
use App\Team;
use App\Project;

class MembersController extends Controller
{
    public function show_members($id)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id))return redirect('/home');

        $project = Project::find($id);
        $this->authorize('isMember', $project);

        $members = Member::join('profile', 'member.id_profile', '=', 'profile.id')->select('member.*', 'profile.username')->where('id_project', $id)->orderBy('member.id', 'DESC')->distinct()->get();

        $teams = Team::where('id_project', $id)->orderBy('id', 'DESC')->get();

        $profilesNotInProject = User::select('profile.id', 'profile.username')->whereNotIn(
            'id',
            User::join('member', 'member.id_profile', '=', 'profile.id')->select('profile.id')->where('id_project', $id)->distinct()->get()
        )->orderBy('profile.id')->get();

        $profilesTeamMembersArray = array();
        $profilesNotInTeamArray = array();

        foreach ($teams as $team) {
            $profilesTeamMembers = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
                ->select('profile.*')->where('team_member.id_team', $team->id)->get();

            $profilesNotInTeam = User::join('member', 'member.id_profile', '=', 'profile.id')->where('member.id_project', $id)->select('profile.*')->whereNotIn(
                'profile.id',
                User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')->select('profile.id')->where('team_member.id_team', $team->id)->get()
            )->orderBy('profile.id')->get();

            array_push($profilesNotInTeamArray, $profilesNotInTeam);
            array_push($profilesTeamMembersArray, $profilesTeamMembers);
        }


        return view('pages.project_members', [
            'members' => $members, 'project' => $project, 'teams' => $teams, 'profilesNotInProject' => $profilesNotInProject,
            'profilesNotInTeamArray' => $profilesNotInTeamArray, 'profilesTeamMembersArray' => $profilesTeamMembersArray
        ]);
    }

    public function searchTeam(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($request['id']))return redirect('/home');

        $project = Project::find($request['id']);
        $this->authorize('isMember', $project);

        $name = $request['name'];
        $teams = Team::whereId_project($request['id'])->where("name", "ILIKE", "%$name%")->orderBy('id', 'DESC')->get();

        $view = '';
        foreach ($teams as $team) {
            $profilesTeamMembers = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
                ->select('profile.*')->where('team_member.id_team', $team->id)->get();

            $profilesNotInTeam = User::join('member', 'member.id_profile', '=', 'profile.id')->where('member.id_project', $request['id'])->select('profile.*')->whereNotIn(
                'profile.id',
                User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')->select('profile.id')->where('team_member.id_team', $team->id)->get()
            )->orderBy('profile.id')->get();
            $view = $view . view('partials.team', ['project'=>$project,'team' => $team, 'profilesTeamMembers' => $profilesTeamMembers, 'profilesNotInTeam' => $profilesNotInTeam]);
        }
        return $view;
    }

    public function searchMembers(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($request['id']))return redirect('/home');

        $project = Project::find($request['id']);
        $this->authorize('isMember', $project);
        $name = $request->input('name');
        $members = DB::table('member')->join('profile', 'profile.id', '=', 'member.id_profile')->select('member.*')->where('member.id_project', $request['id'])->where("profile.name", "ILIKE", "$name%")->get();
        
       $view = '';
        foreach ($members as $member)
            $view = $view . view('partials.member', ['member' => $member]);
        return $view;
    }

    public function addTeam(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $team = new Team();
        $team->id_project = $id;
        $team->name = $request['name'];
        $team->creator = Auth::id();
        $team->save();

        if ($request['members'] != NULL) {
            foreach ($request['members'] as $username) {
                $id_member = User::join('member', 'member.id_profile', '=', 'profile.id')->where('member.id_project', $id)->whereUsername($username)->get('member.id')->first()->id;
                DB::table('team_member')->insert([['id_member' => $id_member, 'id_team' => $team->id],]);
            }
        }

        return redirect()->route('members', ['id' => $id]);
    }

    public function addMember(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        if ($request['members'] != NULL) {
            foreach ($request['members'] as $username) {
                $newMember = new Member();
                $newMember->id_project = $id;
                $newMember->id_profile = User::where('username', $username)->get('id')->first()->id;
                $newMember->coordinator = false;
                $newMember->save();
            }
        }

        return redirect()->route('members', ['id' => $id]);
    }

    public function deleteMember($id, $memberId)
    {

        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id)||!is_numeric($memberId))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $member = Member::find($memberId);
        $member->delete();

        return redirect()->route('members', ['id' => $id]);
    }

    public function upgradeMember($id, $memberId)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id)||!is_numeric($memberId))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $member = Member::find($memberId);
        $member->coordinator = true;
        $member->save();

        return redirect()->route('members', ['id' => $id]);
    }

    public function deleteTeam($id, $teamId)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id)||!is_numeric($teamId))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $team = Team::find($teamId);
        $team->delete();

        return redirect()->route('members', ['id' => $id]);
    }

    public function addTeamMember(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id)||!is_numeric($request['teamID']))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $teamId = $request['teamID'];
        if ($request['members'] != NULL) {
            foreach ($request['members'] as $username) {
                $id_member = User::join('member', 'member.id_profile', '=', 'profile.id')->where('member.id_project', $id)->whereUsername($username)->get('member.id')->first()->id;
                DB::table('team_member')->insert([['id_member' => $id_member, 'id_team' => $teamId],]);
            }
        }
        return redirect()->route('members', ['id' => $id]);
    }

    public function removeTeamMember(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
        if (!is_numeric($id)||!is_numeric($request['teamID']))return redirect('/home');
        $project = Project::find($id);
        $this->authorize('isAdmin', $project);

        $teamId = $request['teamID'];
        if ($request['members'] != NULL) {
            foreach ($request['members'] as $username) {
                $id_member = User::join('member', 'member.id_profile', '=', 'profile.id')->where('member.id_project', $id)->whereUsername($username)->get('member.id')->first()->id;
                TeamMember::where('id_member', $id_member)->where('id_team', $teamId)->delete();
            }
        }
        return redirect()->route('members', ['id' => $id]);
    }
}
