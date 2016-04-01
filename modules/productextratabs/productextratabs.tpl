{foreach from=$tabs item='tab' name='f_tabs'}
    <li><a id="product_extra_tab_{$tab.id_tab}" href="#idTab{$tab.id_tab+20}" role="tab" data-toggle="tab">{$tab.title}</a></li>
{/foreach}