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
{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
                    
					</div><!-- .row -->
                    {if $page_name == 'index'}
                        {if isset($current_option) && $current_option == 2}
                            {hook h='displayHomeBottomColumn'}
                        {/if}
                        {if isset($current_option) && $current_option == 5}
                            {hook h='displayHomeBottomColumn'}
                        {/if}
                    {/if}    
                    {hook h='displayBottomColumn'}
				</div>
			</div>
			{if $page_name == 'index'}
			<div class="group-categories-container">
				<div class="container">
                    {if isset($current_option) && ($current_option == 1 || $current_option == 4)}
                        {hook h='displayHomeBottomColumn'}
                    {/if}
				</div>				
			</div>
			{/if}
			{if isset($HOOK_FOOTER)}
				<!-- Footer -->
				<div class="footer-container">
					<footer id="footer">
						{$HOOK_FOOTER}
					</footer>
				</div><!-- #footer -->
			{/if}
            <a href="#" class="scroll_top" title="Scroll to Top">{l s='Scroll'}</a>
		</div><!-- #page -->
{/if}
{include file="$tpl_dir./global.tpl"}
	</body>
</html>