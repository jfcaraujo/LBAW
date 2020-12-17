<div class="input-group">
  <input
    id="table_filter"
    type="text"
    class="form-control"
    aria-label="Text input with segmented button dropdown"
    maxlength="255"
  />
  <div class="input-group-btn">
    <button
      type="button"
      class="btn btn-secondary dropdown-toggle dropdown-toggle-split"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <span class="label-icon">Category</span>
      <span class="caret">&nbsp;</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
      <ul class="category_filters">
        @foreach ($options as $option)
        <li>
          <input
            class="cat_type category-input"
            data-label="All"
            id="{{ $option }}"
            value="{{ $option }}"
            name="radios"
            type="radio"
          /><label for="{{ $option }}">{{ ucfirst($option) }}</label>
        </li>
        @endforeach
      </ul>
    </div>
    <button id="searchBtn" type="button" class="btn btn-secondary btn-search">
      Search
    </button>
  </div>
</div>
