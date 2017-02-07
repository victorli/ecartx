{foreach $languages as $language}
	<div class="form-group translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
		<div class="col-lg-9">
		    <div id="{$input.name}_{$language.id_lang}" class="sns-additem" data-lang="{$language.id_lang}">
			    <div id="sns_additem_container_{$language.id_lang}" class="additem-container">
			    	<div class="additem-label row">
						{foreach $input.fields as $field}
						<label class="col-xs-{$field.width}">{$field.label}</label>
						{/foreach}
					</div>
					<ul class="list-unstyled list-item">
						{assign var=idlang value=$language.id_lang}
						{if isset($input.data.$idlang) && $input.data.$idlang}
							{foreach from=$input.data.$idlang item=row key=id}
								<li style="margin-bottom: 10px;" class="additem-row row">
									{foreach $input.fields as $field}
										{assign var=fieldname value=$field.name}
										<div class="col-xs-{$field.width}">
										{if $field.type == 'text'}
											<input type="text" value="{if isset($row.$fieldname)}{$row.$fieldname}{/if}" class="form-control" name="{$input.name}_{$idlang}[{$id}][{$field.name}]">
										{elseif $field.type == 'select'}
											{if isset($row.$fieldname)}
												{assign var=fieldvalue value=$row.$fieldname}
											{/if}
											<select name="{$input.name}_{$idlang}[{$id}][{$field.name}]">
												{foreach from=$field.options item=label key=k}
												<option {if isset($fieldvalue) && $fieldvalue == $k}selected="selected"{/if} value="{$k}">{$label}</option>
												{/foreach}
											</select>
										{elseif $field.type == 'color'}
											<div class="input-group">
												<input type="color"
												data-hex="true"
												class="color mColorPickerInput"
												{if isset($row.$fieldname)}
												value="{$row.$fieldname|escape:'html':'UTF-8'}"
												{/if}
												name="{$input.name}_{$idlang}[{$id}][{$field.name}]" />
											</div>
										{elseif $field.type == 'image'}
											<div class="sns-imgfield">
												<div class="input-group">
													<div class="input-group-btn">
														<button type="button" class="btn btn-default img-preview" data-field_id="{$input.name}_{$idlang}{$id}{$field.name}"><i class="icon-eye"></i></button>
													</div>
													<input type="text" value="{if isset($row.$fieldname)}{$row.$fieldname}{/if}" name="{$input.name}_{$idlang}[{$id}][{$field.name}]" id="{$input.name}_{$idlang}{$id}{$field.name}" class="form-control sns-imgfield-input">
													<div class="input-group-btn">
														<button type="button" class="btn btn-default sns-imgfield-open" data-field_id="{$input.name}_{$idlang}{$id}{$field.name}"><i class="icon-folder-open"></i></button>
														<button type="button" class="btn btn-default sns-imgfield-remove"><i class="icon-remove"></i></button>
													</div>
												</div>
											</div>
										{elseif $field.type == 'textarea'}
											<textarea type="text" class="form-control" name="{$input.name}_{$idlang}[{$id}][{$field.name}]">{if isset($row.$fieldname)}{$row.$fieldname}{/if}</textarea>
										{/if}
										</div>
									{/foreach}
									<div class="col-xs-2">
										<button class="btn btn-default remove-item remove-additem" type="button"><span class="icon-trash"></span></button>
										<span class="btn btn-default handle" style="margin-left: 10px; cursor: move;"><span class="icon-arrows"></span></span>
									</div>
								</li>
							{/foreach}
						{/if}
					</ul>
				</div>
				<button type="button" class="btn btn-success btn-additem" id="btn_{$input.name}_{$language.id_lang}">{$input.btn}</button>
			</div>
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
{/foreach}
{if isset($input.iconpicker) && $input.iconpicker}
	{assign var="eq" value=0|rand:100000|cat:$smarty.now}
	<div class="alert alert-info col-lg-9">
		<div class="col-xs-6" style="font-size: 19px;">{l s="Font Awesome icons class"}</div>
		<div class="input-group col-xs-6">
			<div class="input-group-btn">
	            <button class="btn btn-default" id="iconpickerbtn_{$eq}"></button>
			</div>
			<input type="text" id="iconpickerinput_{$eq}" />
			<script>
				$('#iconpickerbtn_' + {$eq}).iconpicker({ 
					arrowClass: 'btn-info',
				    arrowPrevIconClass: 'fa fa-arrow-left',
				    arrowNextIconClass: 'fa fa-arrow-right',
					iconset: 'fontawesome',
				    placement: 'bottom',
				    rows: 4,
				    cols: 8,
				    icon: "",
				    search: true,
				    searchText: 'Search',
				    selectedClass: 'btn-success',
				    unselectedClass: ''
				});
				$('#iconpickerbtn_' + {$eq}).on('change', function(e) { 
				    $('#iconpickerinput_' + {$eq}).val(e.icon);
				});
			</script>
		</div>
	</div>
{/if}
<script type="text/javascript">
	$('[id^="{$input.name}"]').each(function(){
		var _testimonial = $(this);
		var el = $(this);
		var btn = $('[id*="btn_{$input.name}"]', el);
		btn.on('click', function(){
			var items_wrap = $('.list-item', el);
			var lang = el.data('lang');
		    var date = new Date();
		    var now = date.getTime();
			var _eq = '_' + now + Math.random().toString().replace('.', '');
			var template = '';
			template += '<li class="additem-row row" style="margin-bottom: 10px;">';
			{foreach $input.fields as $field}
			template += '<div class="col-xs-{$field.width}">';
			{if $field.type == 'text'}
				template += '<input type="text" name="{$input.name}_' + lang + '[' + _eq + '][{$field.name}]" class="form-control">';
			{elseif $field.type == 'select'}
				template += '<select name="{$input.name}_' + lang + '[' + _eq + '][{$field.name}]">';
				template += '	{foreach from=$field.options item=label key=k}';
				template += '	<option value="{$k}">{$label}</option>';
				template += '	{/foreach}';
				template += '</select>';
			{elseif $field.type == 'color'}
				template += '<div class="input-group"><input id="color_'+_eq+'" type="color" data-hex="true" class="color mColorPickerInput" value="#ffffff" name="{$input.name}_' + lang + '[' + _eq + '][{$field.name}]" /></div>';
			{elseif $field.type == 'image'}
				template += '<div class="sns-imgfield">';
				template += '	<div class="input-group">';
				template += '		<div class="input-group-btn">';
				template += '			<button type="button" class="btn btn-default img-preview" data-field_id="{$input.name}_' + lang + _eq + '{$field.name}"><i class="icon-eye"></i></button>';
				template += '		</div>';
				template += '		<input type="text" value="" name="{$input.name}_' + lang + '[' + _eq + '][{$field.name}]" id="{$input.name}_' + lang + _eq + '{$field.name}" class="form-control sns-imgfield-input">';
				template += '		<div class="input-group-btn">';
				template += '			<button type="button" class="btn btn-default sns-imgfield-open" data-field_id="{$input.name}_' + lang + _eq + '{$field.name}"><i class="icon-folder-open"></i></button>';
				template += '			<button type="button" class="btn btn-default sns-imgfield-remove"><i class="icon-remove"></i></button>';
				template += '		</div>';
				template += '	</div>';
				template += '</div>';
			{elseif $field.type == 'textarea'}
				template += '<textarea type="text" name="{$input.name}_' + lang + '[' + _eq + '][{$field.name}]" class="form-control"></textarea>';
			{/if}
			template += '</div>';
			{/foreach}
			template += '<div class="col-xs-2">';
			template += '<button type="button" class="btn btn-default remove-item remove-additem"><span class="icon-trash"></span></button>';
			template += '<span class="btn btn-default handle" style="margin-left: 10px; cursor: move;"><span class="icon-arrows"></span></span>';
			template += '</div>';
			template += '</li>';
			items_wrap.append(template);
			if($('#color_'+_eq).length) $('#color_'+_eq).mColorPicker();
		});
	});
</script>