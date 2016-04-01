{foreach from=$tabs item='tab' name='f_tabs'}
    <div id="idTab{$tab.id_tab+20}" class="tab-pane fade in">
        {$tab.content}
    </div>
{/foreach}