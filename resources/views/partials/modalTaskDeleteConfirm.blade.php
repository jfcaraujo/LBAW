<div
  class="modal fade"
  id="modalTaskDeleteConfirm-{{$task_id}}"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Are you sure you want to delete this task?
        </h5>
      </div>
      <form method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" id="task_id" name="task_id" value="{{$task_id}}">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="deleteTask({{$task_id}},{{$project_id}})" data-dismiss="modal">
            Delete
          </button>
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
            Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
