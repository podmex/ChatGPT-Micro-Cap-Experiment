@if($pages > 1)
    <div class="s_pager" style="margin:0;padding:4px">
        <ul id="pages" class="clearfix">
            @if($page > 1)
                <li class="s_first inline"><a href="javascript:{{ $obj }}.get_page(1);">&laquo;</a></li>
                <li class="s_prev inline"><a href="javascript:{{ $obj }}.get_page({{ $page - 1 }});">&lsaquo;</a></li>
            @endif
            {math assign="i_loop" equation="min($pages, $page+3+max(0,5-$page))"}
            {math assign="i_start" equation="max($page-3-max(0, 3+$page-$pages), 1)"}
            {section loop=$i_loop+1 start=$i_start name=p}
                <li>
                {if $smarty.section.p.index ne $page}
                    <a href="javascript:{$obj}.get_page({$smarty.section.p.index});">{$smarty.section.p.index}</a>
                {else}
                    <strong>{$page}</strong>
                {/if}
                </li>
            {/section}
            @if($page < $pages)
                <li class="s_next inline"><a href="javascript:{{ $obj }}.get_page({{ $page + 1 }})">&rsaquo;</a></li>
                <li class="s_last inline"><a href="javascript:{{ $obj }}.get_page({{ $pages }})">&raquo;</a></li>
            @endif
        </ul>
    </div>
@endif