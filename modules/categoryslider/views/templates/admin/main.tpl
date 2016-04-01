<div class="panel">
        <h3><i class="icon-cog"></i>{l s=' Slider configuration' mod='categoryslider'}
	</h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            <div class="title item-field form-group">
        		<label id="title_lb" class="control-label col-lg-3 ">Width</label>
                <div class="col-lg-9">
                    <div>
			            <div class="col-lg-9 input-group">
			                <input class="form-control" type="text" id="slide_width" name="slide_width" value="{if isset($slide_width)}{$slide_width}{/if}"/>
                            <span class="input-group-addon">pixels</span>
			            </div>
						<div class="col-lg-2">
						</div>	
                     </div>                     					
        		</div>
        	</div>
            <div class="title item-field form-group">
        		<label id="title_lb" class="control-label col-lg-3 ">Height</label>
                <div class="col-lg-9">
                    <div>
			            <div class="col-lg-9 input-group">
			                <input class="form-control" type="text" id="slide_height" name="slide_height" value="{if isset($slide_height)}{$slide_height}{/if}"/>
                            <span class="input-group-addon">pixels</span>
			            </div>
						<div class="col-lg-2">
						</div>	
                     </div>                     					
        		</div>
        	</div>
            <div class="title item-field form-group">
        		<label id="title_lb" class="control-label col-lg-3 ">Speed</label>
                <div class="col-lg-9">
                    <div>
			            <div class="col-lg-9 input-group">
			                <input class="form-control" type="text" id="slide_speed" name="slide_speed" value="{if isset($slide_speed)}{$slide_speed}{/if}"/>
                            <span class="input-group-addon">milliseconds</span>
			            </div>
                        <p class="help-block">The duration of the transition between two slides.</p>
                     </div>                     					
        		</div>
        	</div>
            <div class="title item-field form-group">
        		<label id="title_lb" class="control-label col-lg-3 ">Pause</label>
                <div class="col-lg-9">
                    <div>
			            <div class="col-lg-9 input-group">
			                <input class="form-control" type="text" id="slide_pause" name="slide_pause" value="{if isset($slide_pause)}{$slide_pause}{/if}"/>
                            <span class="input-group-addon">milliseconds</span>
			            </div>
						<p class="help-block">The delay between two slides.</p>
                     </div>                     					
        		</div>
        	</div>
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Loop</label>
                <div class="col-lg-9">
                    <div>
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="slide_loop" id="loop_on" {if isset($slide_loop) && $slide_loop == 1}checked="checked"{/if} value="1"/>
                                <label for="loop_on">Yes</label>
                                <input type="radio" name="slide_loop" id="loop_off" {if isset($slide_loop) && $slide_loop == 0}checked="checked"{/if} value="0" />
                                <label for="loop_off">No</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                        <div class="col-lg-2">
						</div>	
                    </div>
                </div>
            </div>
            <div class="form-group">
				<div class="col-lg-9 col-lg-offset-3">
					<button type="submit" name="submitSlider" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
        </form>
    </div>
</div>
{$categoryTree}
<input type="hidden" id="mainUrl" class="hidden" value="{$postAction|escape:'htmlall':'UTF-8'}" />
{if $displayList}
    <div class="panel">
        <h3><i class="icon-cog"></i>{l s=' Slider list' mod='categoryslider'}
        	<span class="panel-heading-action">
        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&addSlide&id_category={$current_cate->id_category}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new slide" data-html="true">
        				<i class="process-icon-new "></i>
        			</span>
        		</a>
        	</span>
    	</h3>
        <div class="main-container">
            {if $slides && $slides|count > 0}
                {foreach $slides as $slide}
                <div id="slides_{$slide.id_slide}" class="panel">
					<div class="row">
						{*}<div class="col-lg-1">
							<span><i class="icon-arrows "></i></span>
						</div>{*}
						<div class="col-md-4">
							<img src="{$imgpath}{$slide.image}" alt="{$slide.title}" class="img-thumbnail"/>
						</div>
						<div class="col-md-8">
							<h4 class="pull-left">{$slide.title}</h4>
							<div class="btn-group-action pull-right">
                                {if $slide.active == 1}
								    <a class="btn btn-success" href="{$postAction|escape:'htmlall':'UTF-8'}&changeStatus&&id_slide={$slide.id_slide}&id_category={$slide.id_category}" title="Enabled"><i class="icon-check"></i> Enabled</a>
                                {else}
                                    <a class="btn btn-danger" href="{$postAction|escape:'htmlall':'UTF-8'}&changeStatus&&id_slide={$slide.id_slide}&id_category={$slide.id_category}" title="Disabled"><i class="icon-remove"></i> Disabled</a>
                                {/if}
								<a class="btn btn-default" href="{$postAction|escape:'htmlall':'UTF-8'}&id_slide={$slide.id_slide}&id_category={$slide.id_category}">
									<i class="icon-edit"></i>Edit
								</a>
								<a class="btn btn-default" href="{$postAction|escape:'htmlall':'UTF-8'}&delete_id_slide={$slide.id_slide}&id_category={$slide.id_category}">
									<i class="icon-trash"></i>Delete
								</a>
							</div>
						</div>
					</div>
				</div>
                {/foreach}
            {/if}
         </div>
     </div>
 {/if}
 