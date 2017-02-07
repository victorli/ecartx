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
{if isset($products) && $products}
	{assign var="eq" value=0|rand:100000|cat:$smarty.now}
	

	<div id="prds_{$eq}" class=" products-grid preload tabslider {if isset($class) && $class} {$class}{/if}">

	{foreach from=$products item=product name=products}
		<div class="ajax_block_product item">
			{include file="$tpl_dir./product-blockgrid.tpl"}
		</div>
	{/foreach}
	</div>
	<script type="text/javascript">
	    jQuery(document).ready(function($) {

	    	var owl = $('#prds_{$eq}');    	
	     	 var owl_options = {
			    loop:true,
			    responsiveClass:true,
			    nav: false,
			    dots: true,
	        	items: 4,

				responsive : {
				    0 : { items: 1 },
				    480 : { items: {$SNSTS_XS} },
				    768 : { items: {$SNSTS_SM} },
				    992 : { items: {$SNSTS_MD} },
				    1200 : { items: {$SNSTS_LG} }
				},
			};

	      	owl.owlCarousel(owl_options);

	        $('#sns_producttabsslider .navslider .prev').on('click', function(e){
				e.preventDefault();
			
				$('#prds_{$eq}').trigger('prev.owl.carousel');
			});
			$('#sns_producttabsslider .navslider .next').on('click', function(e){
				e.preventDefault();
				$('#prds_{$eq}').trigger('next.owl.carousel');
			});

			$('.sns-pdt-container .loading').fadeOut();
			$('#prds_{$eq}').removeClass('preload');


			


	    });
	</script>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
