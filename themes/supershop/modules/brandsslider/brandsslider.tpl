{if $manufacturers}
	{if $manufacturers|@count >14}
		{assign var='nextItem' value=0}
		
		    <div  class="brand_list_owl" >
		    	<div>
		        {foreach from=$manufacturers item=manufacturer name=manufacturer_list}
			            <div class="brand_owl_item">
			                <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">
			                    <img src="{$img_manu_dir}{$manufacturer.image}" alt="{$manufacturer.name}"/>
							</a>
			            </div>
			        {if ($smarty.foreach.manufacturer_list.index % 3 == 2) && !$smarty.foreach.manufacturer_list.last} 
						</div>
						
						<div>
				    {/if}
		        {/foreach}
		        </div>	       
		    </div>		    
		
		<script type="text/javascript">
			//<![CDATA
				$(document).ready(function(){
					$('.brand_list_owl').owlCarousel({
						loop:true,
						margin: -1,
						responsiveClass:true,
						nav:false,
						responsive:{
							0:{
								items:3
								
							},
							480:{
								items:4
							},
							768:{
								items:5
							},
							992:{
								items:6
							},
							1200:{
								items:7
							}
						}
					});
				});
			//]]>
		</script>		
	{else}
		<!-- Brands slider module -->
		<div id="brands_wrap">
		    <div id="brand_list" >
		        {foreach from=$manufacturers item=manufacturer name=manufacturer_list}
		            <div class="item test">
		                <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">
		                    <img src="{$img_manu_dir}{$manufacturer.image}" alt="{$manufacturer.name}"/></a>
		            </div>
		        {/foreach}		<div class="clearfix"></div>
		    </div>
		</div>
		<!-- /Brands slider module -->
	{/if}

{/if}