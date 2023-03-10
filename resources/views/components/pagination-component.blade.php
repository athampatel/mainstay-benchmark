<div class="d-flex align-items-center justify-content-between">
    @if(!empty($pagination))
    <div class="pagination_text">{{$pagination['from']}}-{{$pagination['to']}} of {{$pagination['total']}} items</div>
    <div class="d-flex align-items-center">
        <div class="d-flex">
            <div class="pagination_button">
                <a href="{{$pagination['first_page_url']}}">
                    <img src="/assets/images/svg/pagination_first.svg" />
                </a>
            </div>
            <div class="pagination_button">
                <a href="{{$pagination['prev_page_url']}}">
                    <img src="/assets/images/svg/pagination_prev.svg" />
                </a>
            </div>
        </div>
        <div class="d-flex">
            @foreach ($pagination['links'] as $link)
                {{-- @if (!$loop->first && !$loop->last) --}}
                <div class="pagination_button">
                    <a href="{{$link['url']}}&limit={{$pagination['per_page']}}&search={{$search}}" class="btn {{ $link['active'] ? 'pagination_active_button rounded' : ''}}">{{$link['label']}}</a>   
                </div>
                {{-- @endif --}}
            @endforeach
        </div>
        <div class="d-flex">
            <div class="pagination_button">
                <a href="{{$pagination['next_page_url']}}">
                    <img src="/assets/images/svg/pagination_next.svg" />
                </a>
            </div>
            <div class="pagination_button">
                <a href="{{$pagination['last_page_url']}}">
                    <img src="/assets/images/svg/pagination_last.svg" />
                </a>
            </div>
        </div>
    </div>
    {{-- cross check work start --}}
    @else
    <div class="parent" style="display:flex;color:#fff">
        @for($i= 0;$i <= 10;$i++)
        <div class="child" style="padding:3px;">
            <a href="" id="{{$i == 4 ? 'active':''}}">{{$i}}</a>
        </div>
        @endfor
    </div>

    {{-- cross check work end --}}
    @endif 

</div>