<div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div id="pass_alert_wrong"></div>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
          Change Password
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="textOut-b" for="exampleInputName">Current</label>
          <input type="password" name="current_pass" class="form-control" id="current_pass" value="" />
          @if ($errors->has('current_pass'))
          <span class="error">
            {{ $errors->first('current_pass') }}
          </span>
          @endif
        </div>
        <div class="form-group">
          <label class="textOut-b" for="exampleInputName">New</label>
          <input type="password" name="new_pass" class="form-control" id="new_pass" value="" />
          @if ($errors->has('new_pass'))
          <span class="error">
            {{ $errors->first('new_pass') }}
          </span>
          @endif
        </div>
        <div class="form-group">
          <label class="textOut-b" for="exampleInputName">Confirm</label>
          <input type="password" name="new_pass_confirmation" class="form-control" id="new_pass_confirmation" value="" />
          @if ($errors->has('new_pass_confirmation'))
          <span class="error">
            {{ $errors->first('new_pass_confirmation') }}
          </span>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <button id="submit_pass_btn" type="button" class="btn btn-dark">
          Save changes
        </button>
        <button id="cancel_modal_pass" type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>