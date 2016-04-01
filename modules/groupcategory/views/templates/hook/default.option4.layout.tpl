<section id="groupcategory-{$module_id}" class="box-group-category style-{$module_style}">
	<h2 class="heading-title hidden">{$module_name}</h2>
	<div class="groupcategory-tb">
		<div class="groupcategory-tr row">
			<div class="groupcategory-cell col-md-2 col-xs-12 col-sm-4">
				<div class="row" >
					<div class="groupcategory-tabs clearfix">
						<div class="box-header clearfix">
			                <div class="pull-left box-header-icon">
			                	{if $module_icon_type == 'image'}
			                    	<img src="{$module_icon_img}" alt="{$module_name}">			                  			                  
			                    {else}
				                    {if $module_icon_img != ''}
				                    	<i class="{$module_icon_img}"></i>
				                    {/if}
			                    {/if}    
							</div>
			                <div class="pull-left box-header-title">
			                	<a role="tab" data-id="{$module_id}" data-toggle="tab" href=".tab-content-{$module_id}-0-0" class="tab-link check-active active">{$module_name}</a>
			                </div>
			            </div>
						{$sectionFeatures}			            
			            {$sectionSubs}
			            {$sectionManufacturers}
					</div>	
				</div>				
			</div>			
			<div class="groupcategory-cell col-md-7 col-sm-8 col-xs-12">
				<div class="row">
					<div class="tab-content">
						{$sectionContent}
					</div>	
				</div>
			</div>
			<div class="groupcategory-cell  col-md-3 col-xs-12 hidden-sm">
				<div class="row" style="height: inherit">
					{$sectionBanners}	
				</div>				
			</div>
		</div>
		
	</div>
</section>