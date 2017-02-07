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

{if isset($input.lang) AND $input.lang}
	{foreach $languages as $language}
		{if $languages|count > 1}
		<div class="form-group translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
			<div class="col-lg-6">
		{/if}
				<div class="sns-imgfield {if isset($input.class)} {$input.class}{/if}">
					<div class="input-group">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default img-preview" data-field_id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"><i class="icon-eye"></i></button>
						</div>
						<input type="text"
							name="{$input.name}_{$language.id_lang}"
							id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
							value="{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}"
							value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
							class="form-control sns-imgfield-input"
							{if isset($input.size)} size="{$input.size}"{/if}
							{if isset($input.maxchar)} data-maxchar="{$input.maxchar}"{/if}
							{if isset($input.maxlength)} maxlength="{$input.maxlength}"{/if}
							{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
							{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
							{if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
							{if isset($input.required) && $input.required } required="required" {/if}
							{if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
							/>
						<div class="input-group-btn">
							<button type="button" class="btn btn-default sns-imgfield-open" data-field_id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"><i class="icon-folder-open"></i></button>
							<button type="button" class="btn btn-default sns-imgfield-remove"><i class="icon-remove"></i></button>
						</div>
					</div>
				</div>
		{if $languages|count > 1}
			</div>
			<div class="col-lg-2">
				<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
					{$language.iso_code}
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					{foreach from=$languages item=language}
					<li>
						<a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a>
					</li>
					{/foreach}
				</ul>
			</div>
		</div>
		{/if}
	{/foreach}
{else}
	<div class="sns-imgfield {if isset($input.class)} {$input.class}{/if}">
		<div class="input-group col-lg-6">
			<div class="input-group-btn">
				<button type="button" class="btn btn-default img-preview" data-field_id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"><i class="icon-eye"></i></button>
			</div>
			<input type="text"
				name="{$input.name}"
				id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
				value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
				class="form-control sns-imgfield-input"
				{if isset($input.size)} size="{$input.size}"{/if}
				{if isset($input.maxchar)} data-maxchar="{$input.maxchar}"{/if}
				{if isset($input.maxlength)} maxlength="{$input.maxlength}"{/if}
				{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
				{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
				{if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
				{if isset($input.required) && $input.required } required="required" {/if}
				{if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
				/>
			<div class="input-group-btn">
				<button type="button" class="btn btn-default sns-imgfield-open" data-field_id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"><i class="icon-folder-open"></i></button>
				<button type="button" class="btn btn-default sns-imgfield-remove"><i class="icon-remove"></i></button>
			</div>
		</div>
	</div>
{/if}