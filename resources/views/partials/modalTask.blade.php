<div
  class="modal fade"
  id="modalTask"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Create Task
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
          <div class="form-group">
            <label class="textOut-b">Task Title</label>
            <input
              type="text"
              name="name"
              class="form-control"
              id="taskName"
            />
          </div>
          <div class="form-group">
            <label class="textOut-b">Description</label>
            <input
              type="text"
              name="name"
              class="form-control"
              id="taskDescription"
            />
          </div>
          <div class="form-group">
            <label class="textOut-b"> Category </label>
            <input
              type="text"
              name="name"
              class="form-control"
              id="taskCategory"
            />
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="createTask({{ $project_id }}, {{ $list_id }})" type="button" class="btn btn-secondary" data-dismiss="modal">
            Create
          </button>
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
            Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
