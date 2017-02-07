<div style="direction: ltr;" id="sns_cpanel">
	<form action="{$content_dir}" method="get">
    <div class="cpanel-head">{l s="Cpanel"}</div>
    <div class="cpanel-set">
		<div id="sns_cpanel_accor" class="accordion-group">
			<div class="row">
				<div class="form-group clearfix">
					<label class="col-xs-4 control-label">{l s="Theme Color"}</label>
					<div class="col-xs-8">
						<input id="snscpl_themecolor" name="SNS_NAZCP_THEMECOLOR" class="form-control minicolors minicolors-input" type="text" value="{$SNS_NAZ_THEMECOLOR}" />
						<script type="text/javascript">
							(function($){
								$("#snscpl_themecolor").minicolors({
									position: "bottom right",
									changeDelay: 200,
									//letterCase: "",
									theme: "bootstrap"
								});
							})(jQuery)
						</script>
					</div>
				</div>
			</div>
			<div class="accordion-heading">
				<a href="#sns_cpanel_body" data-parent="#sns_cpanel_accor" data-toggle="collapse">{l s="Body"}</a>
			</div>
			<div class="collapse in" id="sns_cpanel_body">
				<div class="accordion-inner">
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group">
								<label>Font Size</label>
								<select name="SNS_NAZCP_FONTSIZE" class="form-control">
									{foreach from=$SNS_NAZ_XMLCFG.fontsizes item=fontsize}
							          <option value="{$fontsize}" {if $SNS_NAZ_FONTSIZE==$fontsize}selected="selected"{/if}>{$fontsize}</option>
							        {/foreach}
								</select>
							</div>
						</div>
						<div class="col-xs-8">
							<div class="form-group">
								<label>Layout</label>
								<select name="SNS_NAZCP_LAYOUTTYPE" class="form-control">
									<option {if 1==$SNS_NAZ_LAYOUTTYPE}selected="selected" {/if}value="1">{l s="Full Width"}</option>
									<option {if 2==$SNS_NAZ_LAYOUTTYPE}selected="selected" {/if}value="2">{l s="Boxed"}</option>
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-12">
							<p><strong>Background only applies for Boxed Layout</strong></p>
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-xs-4 control-label">Bg Color</label>
									<div class="col-xs-8">
										<input id="snscpl_bodycolor" name="SNS_NAZCP_BODYCOLOR" class="form-control minicolors minicolors-input" type="text" value="{$SNS_NAZ_BODYCOLOR}" />
										<script type="text/javascript">
											(function($){
												$("#snscpl_bodycolor").minicolors({
													position: "top right",
													changeDelay: 200,
													//letterCase: "",
													theme: "bootstrap"
												});
											})(jQuery)
										</script>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-12">Bg image</label>
									<div class="sns-patterns col-xs-12">
										{$SNS_NAZ_PATTERN}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<div class="button-action">	
		<input type="submit" class="btn btn-default" value="Reset" name="SNS_NAZCP_RESET"/>
		<input type="submit" class="btn btn-success" value="Apply" name="SNS_NAZCP_APPLY"/>
	</div>
    <div data-placement="right" data-toggle="tooltip" data-original-title="Click to open or close" id="sns_config_btn" class="open">
        <i class="fa fa-cog fa-spin "></i>
    </div>
	</form>
	
	

	<!-- <button id="scsscompile" class="btn btn-info" title="Compile SCSS"><i class="fa fa-refresh fa-2x"></i></button> -->
	<script type="text/javascript">

	CSSStyleSheet.prototype.reload = function reload(){
		// Reload one stylesheet
		// usage: document.styleSheets[0].reload()
		// return: URI of stylesheet if it could be reloaded, overwise undefined
		if (this.href) {
			var href = this.href;
			var i = href.indexOf('?'),
					last_reload = 'last_reload=' + (new Date).getTime();
			if (i < 0) {
				href += '?' + last_reload;
			} else if (href.indexOf('last_reload=', i) < 0) {
				href += '&' + last_reload;
			} else {
				href = href.replace(/last_reload=\d+/, last_reload);
			}
			return this.ownerNode.href = href;
		}
	};
	
	StyleSheetList.prototype.reload = function reload(){
		// Reload all stylesheets
		// usage: document.styleSheets.reload()
		for (var i=0; i<this.length; i++) {
			this[i].reload()
		}
	};


	jQuery(document).ready(function(){
		$('#scsscompile').on('click', function(){
			$('#scsscompile .fa').addClass('fa-spin');
			var query = $.ajax({
				type: 'POST',
				url: baseDir + 'modules/snsnazictheme/scssajax.php',
				data: 'action=compilescss&color1={$SNS_NAZ_THEMECOLOR}',
				dataType: 'json',
				success: function(data) {
					$('#scsscompile .fa').removeClass('fa-spin');
					document.styleSheets.reload();
					//alert(data.result);
				}
			});
		})


	})

	</script>
	
</div>
{* 
<div id="pefect_px" style="position: absolute; left: 0; top: 0; opacity: 0.5">
	<img src="{$img_dir}homepage.jpg" />
</div>
<div id="pefect_px_btns" style="position: fixed; left: 10px; top: 10px;">	
	<input type="text" value="1" class="form-control">
	<button class="btn btn-default up"><i class="fa fa-chevron-up"></i></button>
	<button class="btn btn-default down"><i class="fa fa-chevron-down"></i></button>
	<button class="btn btn-default left"><i class="fa fa-chevron-left"></i></button>
	<button class="btn btn-default right"><i class="fa fa-chevron-right"></i></button>
	<button class="btn btn-default in">Fade In</button>
	<button class="btn btn-default out">Fade Out</button>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	var _img = $('#pefect_px');
	$('#pefect_px_btns button').on('click', function(){
		var run = $('#pefect_px_btns input').val();
		if($(this).hasClass('up')){
			_img.css('top', function(index, value){
				return parseFloat(value) - parseFloat(run);
			});
		} else if($(this).hasClass('down')) {
			_img.css('top', function(index, value){
				return parseFloat(value) + parseFloat(run);
			});
		} else if($(this).hasClass('left')) {
			_img.css('left', function(index, value){
				return parseFloat(value) - parseFloat(run);
			});
		} else if($(this).hasClass('right')) {
			_img.css('left', function(index, value){
				console.log(parseFloat(value), run);
				return parseFloat(value) + parseFloat(run);
			});
		} else if($(this).hasClass('in')) {
			_img.css('opacity', function(index, value){
				return parseFloat(value) + 0.1;
			});
		} else if($(this).hasClass('out')) {
			_img.css('opacity', function(index, value){
				return parseFloat(value) - 0.1;
			});
		}
	});
});
</script>
 *}

