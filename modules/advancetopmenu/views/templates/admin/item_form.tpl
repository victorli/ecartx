<div class="item-container">
      <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="item_id" value="{if isset($item->id_item)}{$item->id_item}{/if}"/>
        <input type="hidden" name="block_id" value="{if isset($item->id_block)}{$item->id_block}{/if}"/>
		<div class="well">
            <div class="item-field form-group">
				<label class="control-label col-lg-3">Type</label>
				<div class="col-lg-9">
                    <div class="form-group">
			            <div class="col-lg-9">
			                <select class="form-control fixed-width-lg" name="linktype" id="linktype" >
        						<option {if isset($item->type) && $item->type =='link'}selected="selected"{/if} value="link">Link</option>
        						<option {if isset($item->type) && $item->type =='img'}selected="selected"{/if} value="img">Image</option>
        						<option {if isset($item->type) && $item->type =='html'}selected="selected"{/if} value="html">Custom html</option>
        					</select>
			            </div>
						<div class="col-lg-2">
						</div>	
                     </div>                     					
				</div>
			</div>
			<div class="title item-field form-group">
				<label id="title_lb" class="control-label col-lg-3 ">Title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
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
            <div id="linkContainer" class="link_detail">
                <div class="item-field form-group">
    				<label class="control-label col-lg-3 ">Icon</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                            <div class="col-lg-9">
            					<input class="form-control" type="text" name="item_icon" value="{if isset($item->icon)}{$item->icon}{/if}"/>
                                <p class="help-block newline">
                                {l s='Ex: "icon-camera". ' mod='advancetopmenu'}
                                <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/3.2.1/icons/">{l s='The complete set of 361 icons in Font Awesome 3.2.1' mod='advancetopmenu'}</a></p>
                            </div>
                            <div class="col-lg-2">
                            </div>
                        </div>
    				</div>
                    	
    			</div>
            </div>
            <div id="link_field" class="link_detail">
                <div class="item-field form-group">
    				<label class="control-label col-lg-3 ">Custom Link</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                            {foreach from=$langguages.all item=lang}
                                <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
        				            <div class="col-lg-9">
                                        <input class="form-control" type="text" id="link_text" placeholder="http://" name="link" value="{if isset($link_text[$lang.id_lang])}{$link_text[$lang.id_lang]}{/if}"/>
        				            </div>
        							<div class="col-lg-2">
                                        <p class="help-block">{l s='or' mod='advancetopmenu'}</p>
        							</div>
        						</div>
    						  {/foreach}
                              <input type="hidden" name="link_value" id="link_value" value="{if isset($item->link)}{$item->link}{/if}"/>
                            </div>
                        </div>                                                            
    				</div>                  
    			</div>
                <div class="item-field form-group">
    				<label class="control-label col-lg-3 ">Prestashop Link</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                            {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
                                    <select class="form-control fixed-width-lg link_select" name="link_select" id="link_select_{$lang.id_lang}" >
                                        <option selected="selected">--</option>
                						{$default_link_option[$lang.id_lang]}
                					</select>
    				            </div>
    							<div class="col-lg-2">
    							</div>
    						</div>
						  {/foreach}
                        </div>                                                            
    				</div>                  
                </div>

            <div id="imgContainer" class="link_detail">
    			<div class="image item-field form-group">
    				<label class="control-label col-lg-3">Image</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                            <div class="col-lg-9">
                                {if isset($item->icon) && isset($item->type) && $item->type == 'img'}
                                    <img class="img-thumbnail" src="{$absoluteUrl}img/{$item->icon}" alt="" />
                                {/if}
            					<input type="file" name="item_img" />
                                <input type="hidden" name="old_img" value="{if isset($item->icon)}{$item->icon}{/if}"/>
                            </div>
                            <div class="col-lg-2">
                            </div>
                        </div>
					</div>                    
    			</div>
            </div>
            <div id="htmlContainer" class="link_detail">
                <div class="html item-field form-group">
    				<label class="control-label col-lg-3">HTML</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <textarea class="rte" name="item_html_{$lang.id_lang}" style="margin-bottom:10px; height:300px;" >{if isset($item->text[$lang.id_lang])}{$item->text[$lang.id_lang]}{/if}</textarea>
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
            </div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3 ">class</label>
				<div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
        					<input type="text" id="custom_class_text" name="custom_class_text" value="{if isset($item->class)}{$item->class}{/if}"/>
                            <input type="hidden" name="custom_class" value="{if isset($item->class)}{$item->class}{/if}" id="custom_class"/>
                        </div>
                        <div class="col-lg-2">
                            <p class="help-block">{l s='or' mod='advancetopmenu'}</p>
                        </div>
                    </div>
				</div>
                
			</div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3 ">Defined class</label>
				<div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
        					<select class="form-control fixed-width-lg" name="custom_class_select" id="custom_class_select">
                                <option selected="selected">--</option>
        						<option value="group_header">Group header</option>
        						<option value="line">Line</option>
        					</select>
                        </div>
                    </div>
				</div>
            </div>
            <div class="item-field form-group ">
                    <label for="active" class="control-label col-lg-3">Active</label>
                    <div class="col-lg-9">
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
			<div class="form-group">
				<div class="col-lg-9 col-lg-offset-3">
					<input type="hidden" name="updateItem" value=""/>
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitnewItem" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>
