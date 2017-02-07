{**
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

<div class="sns-toolbar" id="sns_toolbar">
	<ul>
		{if $SNSTB_COMPARE || $SNSTB_QRCODE}
		<li class="btn-snstoolbar" {if !$SNS_TOOLBAR_DISPLAY}style="display: none;"{/if}>
			<span class="btn button">
				<i class="fa fa-close"></i>
				<span class="tool_label">{l s='Close' mod='snstoolbar'}</span>
			</span>
		</li>
		<li class="btn-snstoolbar showall" {if $SNS_TOOLBAR_DISPLAY}style="display: none;"{/if}>
			<span class="btn button">
				<i class="fa fa-plus"></i>
			</span>
		</li>
		
		{/if}
		{if $SNSTB_COMPARE}
		<li {if !$SNS_TOOLBAR_DISPLAY}style="display: none;"{/if}>
			{include file="$tpl_dir./product-compare.tpl" position="toolbar"}
		</li>
		{/if}
		{if $SNSTB_QRCODE}
		<li class="qr-code" {if !$SNS_TOOLBAR_DISPLAY}style="display: none;"{/if}>
			<span class="btn button qr-code" title="{l s='QR Code' mod='snstoolbar'}">
				<i class="fa fa-qrcode"></i>
				<span class="tool_label">{l s='QR Code' mod='snstoolbar'}</span>
			</span>
			<a title="QR code" class="qr-link" href="#">
				<img src="{$image_link}" />
			</a>
		</li>
		{/if}
		<li {if !$SNS_TOOLBAR_DISPLAY}style="display: none;"{/if}>
			<span class="btn button scroll-top" title="{l s='Back to top' mod='snstoolbar'}">
				<i class="fa fa-angle-up"></i>
				<span class="tool_label">{l s='Top' mod='snstoolbar'}</span>
			</span>
		</li>
	</ul>
</div>
<script type="text/javascript">
	{literal}
	$('#sns_toolbar .scroll-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	$('#sns_toolbar .btn.qr-code').click(function () {
		$('#sns_toolbar .qr-link').toggle();
		return false;
	});
	$('#sns_toolbar .qr-link').click(function (e) {
		e.preventDefault();
		$(this).hide();
	});
	$('#sns_toolbar .btn-snstoolbar').on('click', function(){
		if($(this).hasClass('showall')){
			$.cookie('SNS_TOOLBAR_DISPLAY', '1', { path: '/' });
		} else {
			$.cookie('SNS_TOOLBAR_DISPLAY', '0', { path: '/' });
		}
		$('#sns_toolbar li').toggle();
		return false;
	});
	{/literal}
</script>


