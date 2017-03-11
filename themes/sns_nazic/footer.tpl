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
{if !isset($content_only) || !$content_only}
							</div>
                       		<div id="sns_mainbottom"></div>
						</div><!-- #sns_mainmidle -->
					</div><!-- #sns_main -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="sns_right" class="col-xs-12 col-md-{$right_column_size|intval} column">
							<div class="wrap-in">
								{$HOOK_RIGHT_COLUMN}
							</div>
						</div>
					{/if}
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- #sns_content -->
			

			{if $page_name == 'index'}
				{include file="$tpl_dir./index-aftercontent.tpl"}
			{/if}


			{if $page_name == 'index' && isset($SNS_NAZ_OURBRAND_STATUS) && $SNS_NAZ_OURBRAND_STATUS == 1}
				{include file="$tpl_dir./partners.tpl"}
			{/if}

			
			
			<!-- Footer -->
			
			
			{if 
				!($mobile_device) &&
				(isset($SNS_NAZ_FMIDDLE1) && $SNS_NAZ_FMIDDLE1) || 
				(isset($SNS_NAZ_FMIDDLE2) && $SNS_NAZ_FMIDDLE2) || 
				(isset($SNS_NAZ_FMIDDLE3) && $SNS_NAZ_FMIDDLE3) ||
				(isset($SNS_NAZ_FMIDDLE4) && $SNS_NAZ_FMIDDLE4)
			}
			<div id="sns_footer_top" class="footer wrap">
				<div class="container">
					<div class="row">
						{if isset($SNS_NAZ_FMIDDLE1) && $SNS_NAZ_FMIDDLE1}
						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							{$SNS_NAZ_FMIDDLE1}
						</div>
						{/if}
						
						{if isset($SNS_NAZ_FMIDDLE2) && $SNS_NAZ_FMIDDLE2}
						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							{$SNS_NAZ_FMIDDLE2}
						</div>
						{/if}
						
						{if isset($SNS_NAZ_FMIDDLE3) && $SNS_NAZ_FMIDDLE3}
						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							{$SNS_NAZ_FMIDDLE3}
						</div>
						{/if}

						{if isset($SNS_NAZ_FMIDDLE4) && $SNS_NAZ_FMIDDLE4}
						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							{$SNS_NAZ_FMIDDLE4}
						</div>
						{/if}

						{if isset($SNS_NAZ_FMIDDLE5) && $SNS_NAZ_FMIDDLE5}
						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							{$SNS_NAZ_FMIDDLE5}
						</div>
						{/if}

						<div class="col-phone-12 col-xs-6 col-sm-4 col-md-2 column column1">
							<div class="sns-social">
								<div class="lable">
									<h3>
										{l s = 'Follow us'}
									</h3>
								</div>
								{if isset($SNS_NAZ_SOCIAL) && $SNS_NAZ_SOCIAL} 
									<ul class="sns-socials list-solicals clearfix">
										{foreach from=$SNS_NAZ_SOCIAL item=social name=socials}
											<li><a class="fa {$social.icon|escape:'html':'UTF-8'}" title="{$social.title|escape:'html':'UTF-8'}" target="{$social.target|escape:'html':'UTF-8'}" href="{$social.link}" data-original-title="{$social.title|escape:'html':'UTF-8'}" data-toggle="tooltip"></a></li>
										{/foreach}
									</ul>
								{/if}
							</div>

							{if isset($SNS_NAZ_PAYMENTLOGO)}
							<h3>
								{l s = 'Payment method'}
							</h3>
							<div class="payment">
								<img title="{l s='Payment methods'}" alt="{l s='Payment methods'}" src="{$SNS_NAZ_PAYMENTLOGO}" />
							</div>
							{/if}

						</div>


					</div>
				</div>
			</div>
			{/if}
			
			<div class="footer wrap" id="sns_footer_bottom">
				<div class="container">
					{if isset($HOOK_FOOTER)}
						<div class="hook_footer">
						{$HOOK_FOOTER}
						</div>
					{/if}
					<div class="row">
						<div class="col-sm-6">
							<div class="sns-copyright">
								{$SNS_NAZ_COPYRIGHT}
							</div>
						</div>
						<div class="col-sm-6">
							{if isset($SNS_NAZ_FMORELINKS)}
							<div class="more_linls">
								{$SNS_NAZ_FMORELINKS}
							</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
			<!-- end footer -->
		</div><!-- #sns_wrapper -->
		</div><!-- overlay -->
{/if}
		{include file="$tpl_dir./footer-beforeend.tpl"}
		{include file="$tpl_dir./global.tpl"}
	</body>
</html>


