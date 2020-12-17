<div
  class="modal fade"
  id="exampleModalCenter"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Create a List
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form>
        <div class="modal-body">
          <div class="form-group">
            <label class="textOut-b">List Name</label>
            <input id="name" type="text" class="form-control" required="required" maxlength="255"/>
          </div>
          <div class="form-group">
            <label class="textOut-b">Assign Team</label>
            <select id="mySelect" class="selectpicker form-control " multiple data-actions-box="true">
              @foreach($teams as $team)
              <option> {{ $team->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="createTaskList({{ $project_id }})" type="button" class="btn btn-secondary" data-dismiss="modal">
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