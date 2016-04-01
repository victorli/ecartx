{if isset($categoryslider_slides)}
<div id="responsive_slides">
    <div class="callbacks_container clearBoth">
      <ul id="categoryslider">
        {foreach from=$categoryslider_slides item=slide}
	       {if $slide.active}
                <li><img class="img-responsive" src="{$smarty.const._MODULE_DIR_}/categoryslider/images/{$slide.image|escape:'htmlall':'UTF-8'}" alt="{$slide.legend|escape:'htmlall':'UTF-8'}"  /></li>
           {/if}
        {/foreach}
      </ul>
    </div>
</div>
{/if}

