<div
  class="modal fade"
  id="modalListDeleteConfirm-{{$list_id}}"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Are you sure you want to delete this list?
        </h5>
      </div>
      <form method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" id="list_id" name="list_id" value="{{$list_id}}">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="deleteTasklist({{$list_id}})" data-dismiss="modal">
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
