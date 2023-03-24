{{-- <div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div> --}}

<div class="d-flex align-items-center justify-content-between">
    @if(!empty($pagination))
    <div class="pagination_text">{{$pagination['from']}}-{{$pagination['to']}} of {{$pagination['total']}} items</div>
    <div class="d-flex align-items-center" id="pagination_display_div1">
        <div class="btn btn-primary btn-rounded text-capitalize bm-btn-primary text-dark d-none" id="vmi-post-count-bottom">Post Counts</div>
        <div id="pagination_display_div2">
            <div class="d-flex">
                <input type="hidden" name="" id="pagination_path" value="{{$pagination['path']}}">
                <input type="hidden" name="" id="current_page" value="{{$pagination['curr_page']}}">
                <div class="pagination_button">
                    <a href="" data-val="{{$pagination['first_page']}}" class="pagination_link" >
                        <img src="/assets/images/svg/pagination_first.svg" />
                    </a>
                </div>
                <div class="pagination_button">
                    <a href="" data-val="{{$pagination['prev_page']}}" class="pagination_link">
                        <img src="/assets/images/svg/pagination_prev.svg" />
                    </a>
                </div>
            </div>
            <div class="d-flex">
                @foreach ($pagination['links'] as $link)
                    <div class="pagination_button">
                        <a href="" data-val="{{ $link['label'] - 1}}" class="btn {{ $link['active'] ? 'pagination_active_button rounded' : ''}} pagination_link">{{$link['label']}}</a>   
                    </div>
                @endforeach
            </div>
            <div class="d-flex">
                <div class="pagination_button">
                    <a href='' data-val="{{$pagination['next_page']}}" class="pagination_link">
                        <img src="/assets/images/svg/pagination_next.svg" />
                    </a>
                </div>
                <div class="pagination_button">
                    <a href="" data-val="{{$pagination['last_page']}}" class="pagination_link">
                        <img src="/assets/images/svg/pagination_last.svg" />
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif  
</div>