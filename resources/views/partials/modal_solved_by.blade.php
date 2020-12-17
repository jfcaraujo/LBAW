<div
  class="modal fade"
  id="modalSolvedBy-{{ $task_id }}"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Solved by:
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
              id="solvedBy_task{{$task->id}}"
              class="selectpicker form-control"
              data-actions-box="true"
              name="members[]"
            >
            @foreach($possible_solved_by as $member)
              <option> {{ $member }}</option>
            @endforeach
            </select>
        </div>
        <div class="modal-footer">
          <button onclick="solveTask( {{ $task_id }} )" type="button" class="btn btn-secondary" data-dismiss="modal">
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
