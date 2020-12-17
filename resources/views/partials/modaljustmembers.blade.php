<div
  class="modal fade"
  id="modalJustMembers"
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
      <form method="POST" enctype="multipart/form-data" action="{{ route('addMember', $id) }}">
        {{ csrf_field() }}
        <div class="modal-body">
          <select class="selectpicker form-control" data-actions-box="true" name="members[]" multiple>
            @each('partials.modalProfileName', $profiles, 'profile')  
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" >
            Save changes
          </button>
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal" aria-label="Close">
            Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
