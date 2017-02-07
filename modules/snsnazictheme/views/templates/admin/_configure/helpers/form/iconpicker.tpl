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
			<div class="input-group fixed-width-xl">
				<div class="input-group-btn">
	                <button class="btn btn-default" id="{$input.name}_{$language.id_lang}_iconpickerbtn"></button>
				</div>
				<input type="text"
					name="{$input.name}_{$language.id_lang}"
					id="{$input.name}_{$language.id_lang}"
					value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
					class="{if isset($input.class)}{$input.class}{/if}"
					{if isset($input.required) && $input.required } required="required" {/if}
					{if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
					/>
				<script>
					$('#{$input.name}_{$language.id_lang}_iconpickerbtn').iconpicker({ 
						arrowClass: 'btn-info',
					    arrowPrevIconClass: 'fa fa-arrow-left',
					    arrowNextIconClass: 'fa fa-arrow-right',
						iconset: 'fontawesome',
					    placement: 'bottom',
					    rows: 4,
					    cols: 8,
					    icon: "{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}",
					    search: true,
					    searchText: 'Search',
					    selectedClass: 'btn-success',
					    unselectedClass: ''
					});
					$('#{$input.name}_{$language.id_lang}_iconpickerbtn').on('change', function(e) { 
					    $('#{$input.name}_{$language.id_lang}').val(e.icon);
					});
				</script>
			</div>
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
	{if $languages|count > 1}
	</div>
	{/if}
{else}
	{assign var='value_text' value=$fields_value[$input.name]}
	<div class="input-group fixed-width-xl">
		<div class="input-group-btn">
            <button class="btn btn-default" id="{$input.name}_iconpickerbtn"></button>
		</div>
		<input type="text"
			name="{$input.name}"
			id="{$input.name}"
			value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
			class="{if isset($input.class)}{$input.class}{/if}"
			{if isset($input.required) && $input.required } required="required" {/if}
			{if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
			/>
		<script>
			$('#{$input.name}_iconpickerbtn').iconpicker({ 
				arrowClass: 'btn-info',
			    arrowPrevIconClass: 'fa fa-arrow-left',
			    arrowNextIconClass: 'fa fa-arrow-right',
				iconset: 'fontawesome',
			    placement: 'bottom',
			    rows: 4,
			    cols: 8,
			    icon: "{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}",
			    search: true,
			    searchText: 'Search',
			    selectedClass: 'btn-success',
			    unselectedClass: ''
			});
			$('#{$input.name}_iconpickerbtn').on('change', function(e) { 
			    $('#{$input.name}').val(e.icon);
			});
		</script>
	</div>
{/if}