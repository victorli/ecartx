{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
{if $page_name =='index' && isset($revhome) && !empty($revhome)}
    {if $current_option == 3}
        <div class="row home-slider">
            <div class="col-sm-12 displayHomeSlider">{$revhome}</div>
        </div>
    {else}
        <div class="row home-slider">
            <div class="col-lg-3 col-md-3 home-slider-left"></div>
            <div class="col-lg-9 col-md-9 col-sm-12 displayHomeSlider">{$revhome}</div>
        </div>
    {/if}    
	
{else}
    {$revhome}
{/if}
 