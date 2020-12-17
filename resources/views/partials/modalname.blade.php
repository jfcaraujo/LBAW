<div
  class="modal fade"
  id="modalEdit-{{ $list_id }}"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Edit List
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
            <label class="textOut-b">Title</label>
            <input
              type="text"
              name="name"
              class="form-control"
              id="listName{{$list_id}}"
              required="required"
              value="{{$list->name}}"
            />
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="editTaskList( {{ $list_id }} )" type="button" class="btn btn-secondary" data-dismiss="modal">
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
