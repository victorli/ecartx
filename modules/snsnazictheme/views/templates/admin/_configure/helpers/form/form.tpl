{*
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*}

{extends file="helpers/form/form.tpl"}

{block name="defaultForm"}
    <div id="snsp-tabs" class="sns-tabs">
        {foreach $snstabs as $tabTitle => $tabClass}
            <span class="tab" data-tab="{$tabClass}">
                {$tabTitle}
            </span>
        {/foreach}
    </div>
    {$smarty.block.parent}
    <div class="sns-copyright">
    	{$theme_info}
    	<br />
    	<a target="_blank" href="http://snstheme.com">&copy; SNSTheme.com</a>
    </div>
{/block}

{block name="script"}
	{literal}
		function objToString(obj) {
			var str = '';
			for (var p in obj) {
				if (obj.hasOwnProperty(p)) {
					str += '&' + p + '=' + obj[p];
				}
			}
			return str;
		}
	{/literal}
	$('#sns_clearcss').on('click', function(){
		var that = $(this);
		that.attr('class', 'btn btn-warning disabled');
		that.find('i').attr('class', 'icon-spinner icon-spin');
		var params = {
			action : 'clearCss',
			ajax : 1
		};
		var query = $.ajax({
			type: 'POST',
			url: '{$controller_url}',
			data: objToString(params),
			dataType: 'json',
			success: function(data) {
				if(data.success){
					that.attr('class', 'btn btn-success disabled');
					that.find('i').attr('class', 'icon-eraser');
					$.growl({ 
						title: "", 
						message: "The css has been removed successfully.",
						namespace: 'growl',
						duration: 3200,
						close: '<i class="icon-remove"></i>',
						location: "default",
						size: "large",
						style: "notice"
					});
				} else {
					that.attr('class', 'btn btn-danger');
					that.find('i').attr('class', 'icon-frown');
					$.growl.error({ title: "", message: "Please try again." });
				}
			}
		});
	});
	$('.btn-section-submit').on('click', function(){
		tinyMCE.triggerSave();
		var _btn = $(this);
		_btn.attr('disabled', 'disabled');
		_btn.find('i').attr('class', 'process-icon-loading');
		
		var params = {
			action : 'sectionSubmit',
			name : _btn.attr('name'),
			fields : _btn.parents('form').serialize(),
			ajax : 1
		};
		var query = $.ajax({
			type: 'POST',
			url: '{$controller_url}',
			data: objToString(params),
			dataType: 'json',
			success: function(data) {
				if(data.success) {
					_btn.removeAttr('disabled');
					_btn.find('i').attr('class', 'process-icon-save');
					$.growl({ 
						title: "", 
						message: "The settings has been updated successfully.",
						namespace: 'growl',
						duration: 3200,
						close: '<i class="icon-remove"></i>',
						location: "default",
						size: "large",
						style: "notice"
					});
				} else {
					_btn.removeAttr('disabled');
					_btn.find('i').attr('class', 'process-icon-save');
					$.growl.error({ title: "", message: "Please try again." });
				}
			    $('html, body').animate({
			        scrollTop: 0
			    }, 500);
			}
		});
	});
{/block}


{block name="input"}
    {if $input.type == 'pattern_choice'}
    	{$patterns_html}
   {elseif $input.type == 'iconpicker'}
    	{include file='./iconpicker.tpl'}
   {elseif $input.type == 'btn_clearcss'}
		<div class="alert alert-warning">
		  	<p>{l s='"Clear CSS" will delete all of the theme css file and regenerate from sass.'}</p>
		  	<br />
			<button id="sns_clearcss" class="btn btn-success" type="button">
				<i class="icon-eraser"></i> {l s="Clear CSS"}
			</button>
		</div>
    {elseif $input.type == 'additem'}
    	{include file='./additem.tpl'}
    {elseif $input.type == 'js_editor'}
		<textarea name="{$input.name}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}" {if isset($input.cols)}cols="{$input.cols}"{/if} {if isset($input.rows)}rows="{$input.rows}"{/if} class="{if isset($input.class)} {$input.class}{/if}">{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
		<script>
			editAreaLoader.init({
				id: "{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
				,start_highlight: true
				,font_size: "8"
				,font_family: "verdana, monospace"
				,allow_resize: "both"
				,allow_toggle: true
				,syntax: "js"
				,min_width: 500
				,min_height: 200
				,browsers: "all"
				,is_editable: true
				,change_callback: "jsEditorChange"
			});
			function jsEditorChange (id, content) {
				document.getElementById("{if isset($input.id)}{$input.id}{else}{$input.name}{/if}").value = editAreaLoader.getValue("{if isset($input.id)}{$input.id}{else}{$input.name}{/if}");
			}
		</script>
    {elseif $input.type == 'css_editor'}
		<textarea name="{$input.name}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}" {if isset($input.cols)}cols="{$input.cols}"{/if} {if isset($input.rows)}rows="{$input.rows}"{/if} class="{if isset($input.class)} {$input.class}{/if}">{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
		<script>
			editAreaLoader.init({
				id: "{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
				,start_highlight: true
				,font_size: "8"
				,font_family: "verdana, monospace"
				,allow_resize: "both"
				,allow_toggle: true
				,syntax: "css"
				,min_width: 500
				,min_height: 200
				,browsers: "all"
				,is_editable: true
				,change_callback: "cssEditorChange"
			});
			function cssEditorChange (id, content) {
				document.getElementById("{if isset($input.id)}{$input.id}{else}{$input.name}{/if}").value = editAreaLoader.getValue("{if isset($input.id)}{$input.id}{else}{$input.name}{/if}");
			}
		</script>
    {elseif $input.type == 'sns_image'}
    	{include file='./snsimage.tpl'}
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
