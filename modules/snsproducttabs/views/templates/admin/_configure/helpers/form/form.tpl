{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}
$(document).ready(function(){
$('#menuOrderUp').click(function(e){
	e.preventDefault();
    move(true);
});
$('#menuOrderDown').click(function(e){
    e.preventDefault();
    move();
});
$("#items").closest('form').on('submit', function(e) {
	$("#items option").prop('selected', true);
});
$("#addItem").click(add);
$("#availableItems").dblclick(add);
$("#removeItem").click(remove);
$("#items").dblclick(remove);
function add()
{
	$("#availableItems option:selected").each(function(i){
		var val = $(this).val();
		var text = $(this).text();
		text = text.replace(/(^\s*)|(\s*$)/gi,"");
		if (val == "PRODUCT")
		{
			val = prompt('{l s="Set ID product" mod='blocktopmenu' js=1}');
			if (val == null || val == "" || isNaN(val))
				return;
			text = '{l s="Product ID" mod='blocktopmenu' js=1}'+val;
			val = "PRD"+val;
		}
		$("#items").append('<option value="'+val+'" selected="selected">'+text+'</option>');
	});
	serialize();
	return false;
}
function remove()
{
	$("#items option:selected").each(function(i){
		$(this).remove();
	});
	serialize();
	return false;
}
function serialize()
{
	var options = "";
	$("#items option").each(function(i){
		options += $(this).val()+",";
	});
	$("#itemsInput").val(options.substr(0, options.length - 1));
}
function move(up)
{
        var tomove = $('#items option:selected');
        if (tomove.length >1)
        {
                alert('{l s="Please select just one item" mod='blocktopmenu'}');
                return false;
        }
        if (up)
                tomove.prev().insertAfter(tomove);
        else
                tomove.next().insertBefore(tomove);
        serialize();
        return false;
}
});
{/block}

{block name="input"}
    {if $input.type == 'prd_ids'}
			{if isset($input.lang) AND $input.lang}
				{if $languages|count > 1}
				<div class="form-group">
				{/if}
				{foreach $languages as $language}
					{assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
					{if $languages|count > 1}
					<div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
						<div class="col-lg-9">
					{/if}
							{if $input.type == 'prd_ids'}
								{literal}
									<script type="text/javascript">
										$().ready(function () {
											var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
											$('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add Id' js=1}{literal}'});
											$({/literal}'#{$table}{literal}_form').submit( function() {
												$(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
											});
										});
									</script>
								{/literal}
							{/if}
							{if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
							<div class="input-group{if isset($input.class)} {$input.class}{/if}">
							{/if}
							{if isset($input.maxchar)}
							<span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
								<span class="text-count-down">{$input.maxchar}</span>
							</span>
							{/if}
							{if isset($input.prefix)}
								<span class="input-group-addon">
								  {$input.prefix}
								</span>
								{/if}
							<input type="text"
								id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
								name="{$input.name}_{$language.id_lang}"
								class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'prd_ids'} tagify{/if}"
								value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
								onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
								{if isset($input.size)} size="{$input.size}"{/if}
								{if isset($input.maxchar)} data-maxchar="{$input.maxchar}"{/if}
								{if isset($input.maxlength)} maxlength="{$input.maxlength}"{/if}
								{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
								{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
								{if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
								{if isset($input.required) && $input.required} required="required" {/if}
								{if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
								{if isset($input.suffix)}
								<span class="input-group-addon">
								  {$input.suffix}
								</span>
								{/if}
							{if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
							</div>
							{/if}
					{if $languages|count > 1}
						</div>
						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
								{$language.iso_code}
								<i class="icon-caret-down"></i>
							</button>
							<ul class="dropdown-menu">
								{foreach from=$languages item=language}
								<li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
								{/foreach}
							</ul>
						</div>
					</div>
					{/if}
				{/foreach}
				{if isset($input.maxchar)}
				<script type="text/javascript">
				function countDown($source, $target) {
					var max = $source.attr("data-maxchar");
					$target.html(max-$source.val().length);

					$source.keyup(function(){
						$target.html(max-$source.val().length);
					});
				}

				$(document).ready(function(){
				{foreach from=$languages item=language}
					countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
				{/foreach}
				});
				</script>
				{/if}
				{if $languages|count > 1}
				</div>
				{/if}
				{else}
					{if $input.type == 'prd_ids'}
						{literal}
						<script type="text/javascript">
							$().ready(function () {
								var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
								$('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add Id'}{literal}'});
								$({/literal}'#{$table}{literal}_form').submit( function() {
									$(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
								});
							});
						</script>
						{/literal}
					{/if}
					{assign var='value_text' value=$fields_value[$input.name]}
					{if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
					<div class="input-group{if isset($input.class)} {$input.class}{/if}">
					{/if}
					{if isset($input.maxchar)}
					<span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar}</span></span>
					{/if}
					{if isset($input.prefix)}
					<span class="input-group-addon">
					  {$input.prefix}
					</span>
					{/if}
					<input type="text"
						name="{$input.name}"
						id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
						value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
						class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'prd_ids'} tagify{/if}"
						{if isset($input.size)} size="{$input.size}"{/if}
						{if isset($input.maxchar)} data-maxchar="{$input.maxchar}"{/if}
						{if isset($input.maxlength)} maxlength="{$input.maxlength}"{/if}
						{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
						{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
						{if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
						{if isset($input.required) && $input.required } required="required" {/if}
						{if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
						/>
					{if isset($input.suffix)}
					<span class="input-group-addon">
					  {$input.suffix}
					</span>
					{/if}

					{if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
					</div>
					{/if}
					{if isset($input.maxchar)}
					<script type="text/javascript">
					function countDown($source, $target) {
						var max = $source.attr("data-maxchar");
						$target.html(max-$source.val().length);

						$source.keyup(function(){
							$target.html(max-$source.val().length);
						});
					}
					$(document).ready(function(){
						countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
					});
					</script>
					{/if}
				{/if}
    {elseif $input.type == 'link_choice'}
	    <div class="row">
	    	<div class="col-lg-1">
	    		<h4 style="margin-top:5px;">{l s='Change position' mod='blocktopmenu'}</h4> 
                <a href="#" id="menuOrderUp" class="btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-up"></i></a><br/>
                <a href="#" id="menuOrderDown" class="btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-down"></i></a><br/>
	    	</div>
	    	<div class="col-lg-4">
	    		<h4 style="margin-top:5px;">{l s='Selected items' mod='blocktopmenu'}</h4>
	    		{$selected_links}
	    	</div>
	    	<div class="col-lg-4">
	    		<h4 style="margin-top:5px;">{l s='Available items' mod='blocktopmenu'}</h4>
	    		{$choices}
	    	</div>
	    	
	    </div>
	    <br/>
	    <div class="row">
	    	<div class="col-lg-1"></div>
	    	<div class="col-lg-4"><a href="#" id="removeItem" class="btn btn-default"><i class="icon-arrow-right"></i> {l s='Remove' mod='blocktopmenu'}</a></div>
	    	<div class="col-lg-4"><a href="#" id="addItem" class="btn btn-default"><i class="icon-arrow-left"></i> {l s='Add' mod='blocktopmenu'}</a></div>
	    </div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
