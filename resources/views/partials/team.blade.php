<!-- $team => uma equipa do projeto  -->
<div class="col-12 mt-1">
    <div class="card tabMem">
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <h5 class="card-title"> {{ $team->name }} </h5>
                </div>
                <div class="col-3 dropdown d-flex justify-content-end">
                    @can('isAdmin', $project)
                    <button class="btn dropleft" style="padding-top: 0px" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">

                        <a class="dropdown-item" data-toggle="modal" data-target="#modalmembers-{{ $team->id }}add">Add member</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#modalmembers-{{ $team->id }}remove">Remove member</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('deleteTeam', [$team->id_project, $team->id])}}">Delete</a>

                    </div>
                    @endcan
                </div>
            </div>
            @can('isAdmin', $project)
            @include('partials.modaladdmembers')
            @include('partials.modalremovemembers')
            @endcan

            <div class="row">
                @each('partials.teamMember', $profilesTeamMembers, 'profile')

            </div>
        </div>
    </div>
</div>