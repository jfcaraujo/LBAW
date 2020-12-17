<div
  class="modal fade"
  id="modalCreateTeam"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Create a Team
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
      <form method="POST" enctype="multipart/form-data" action="{{ route('addTeam', $id) }}">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <label class="textOut-b" for="exampleInputName">Team Name</label>
            <input
              type="text"
              name="name"
              class="form-control"
              id="exampleInputName"
              required
              maxlength="255"
            />
          </div>
          <div class="form-group">
            <label class="textOut-b">Add Members</label>
            <select
              id="mySelect"
              class="selectpicker form-control"
              multiple
              data-actions-box="true"
              name="members[]"
            >
            @each('partials.modalProfileName', $members, 'profile')  
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" >
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
