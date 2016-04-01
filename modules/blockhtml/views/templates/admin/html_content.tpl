<div class="title item-field form-group">
	<label id="title_lb" class="control-label col-lg-2 ">Title</label>
    <div class="col-lg-10">
        <div class="form-group">
            {foreach from=$langguages.all item=lang}
                <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
		            <div class="col-lg-10">
		                <input class="form-control" type="text" id="item_title" name="item_title_{$lang.id_lang}" value="{if isset($item->title[$lang.id_lang])}{$item->title[$lang.id_lang]}{/if}"/>
		            </div>
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$lang.iso_code|escape:'htmlall':'UTF-8'}
							<i class="icon-caret-down"></i>
						</button>
						{$lang_ul}
					</div>
				</div>
			  {/foreach}	
         </div>                     					
	</div>
</div>
<div class="html item-field form-group">
	<label class="control-label col-lg-2">HTML</label>
	<div class="col-lg-10">
        <div class="form-group">
        {foreach from=$langguages.all item=lang}
            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
	            <div class="col-lg-10">
	                <textarea class="rte" name="item_html_{$lang.id_lang}" style="margin-bottom:10px; height:300px;" >{if isset($item->content[$lang.id_lang])}{$item->content[$lang.id_lang]}{/if}</textarea>
	            </div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
						{$lang.iso_code|escape:'htmlall':'UTF-8'}
						<i class="icon-caret-down"></i>
					</button>
					{$lang_ul}
				</div>
			</div>
		  {/foreach}	
     </div> 
	</div>
</div>
<div class="item-field form-group ">
    <label for="active" class="control-label col-lg-2">Active</label>
    <div class="col-lg-10">
        <div class="form-group">
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="active" id="active_on" {if isset($item->active)&&$item->active == 1 }checked="checked"{/if} value="1"/>
                    <label for="active_on">Yes</label>
                    <input type="radio" name="active" id="active_off" {if isset($item->active)&&$item->active == 0 || !isset($item->active)}checked="checked"{/if} value="0" />
                    <label for="active_off">No</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
            <div class="col-lg-2">
			</div>	
        </div>
    </div>
</div>