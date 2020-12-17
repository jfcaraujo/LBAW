<div
  class="modal fade"
  id="modalTeams"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Teams
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
      <form>
        <div class="modal-body">
          <select
            id="selectedTeamsAssigned"
            class="selectpicker form-control"
            multiple
            data-actions-box="true"
          >
          @foreach($teams as $team)
              <option> {{ $team->team_name }}</option>
          @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button onclick="assignTaskList( {{ $project_id }}, {{ $list->id }})" type="button" class="btn btn-secondary" data-dismiss="modal">
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
