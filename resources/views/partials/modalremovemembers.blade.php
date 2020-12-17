<div
  class="modal fade"
  id="modalmembers-{{ $team->id }}remove"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Members
        </h5>
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" enctype="multipart/form-data" action="{{ route('removeTeamMember', $team->id_project) }}">
        {{ csrf_field() }}
        <input type="hidden" name="teamID" value="{{$team->id}}">
        <div class="modal-body">
          <select class="selectpicker form-control" data-actions-box="true" multiple name="members[]">
            @each('partials.modalProfileName', $profilesTeamMembers, 'profile')  
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" >
            Save changes
          </button>
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
            Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
