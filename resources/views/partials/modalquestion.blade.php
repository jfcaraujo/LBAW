<div class="modal fade" id="modal_question" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add Question
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="new-question-text" class="col-form-label">Question:</label>
            <textarea class="form-control" id="new-question-text" maxlength="500"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" onclick="addQuestion({{$id}})" data-dismiss="modal">
          Save changes
        </button>
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>