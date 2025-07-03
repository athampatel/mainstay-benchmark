<div class="position-relative datatable-filter-div">
    <select name="{{ $name }}" id="{{ $attributes->get('id', $name) }}" {{ $attributes->merge(['class' => 'form-select']) }}>        
        <option value="20" selected>20 Items</option>
        <option value="50">50 Items</option>
        <option value="100">100 Items</option>
    </select>
    <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">    
    <input type="hidden" name="sorting_dir" id="sorting_dir" value="desc">
</div>