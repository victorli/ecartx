<section id="discountproducts" class="col-sm-12">
    <div class="row">
        <h2 class="heading-title">
            <span class="mainFont">{l s='Deals of the day' mod='discountproducts'}</span>
            <span class="pull-right coundown-title">
                <span>{l s='ends in' mod='discountproducts'}</span>
                <i class="icon-time"></i>
                <span id="deals_day"></span>
                <script type="text/javascript">
                    $(function () {ldelim}
                    	var austDay = new Date();
                        austDay = new Date({$deals_day.y},{$deals_day.m -1 },{$deals_day.d},{$deals_day.h},{$deals_day.i},{$deals_day.s});
                        var endtext = '{$expiryText}';
                    	//austDay = new Date('$product.specific_prices.to');
                    	$('#deals_day').countdown({ldelim}until: austDay, padZeroes: true,compact: true,description: '', expiryText: endtext{rdelim});
                    {rdelim});
                </script>
            </span>
        </h2>
        {if isset($products)}
    	   {*}{include file="$tpl_dir./product-list-carousel.tpl" products=$products id='discountproducts_list'}{*}
           {include file="$tpl_dir./product-list-home.tpl" products=$products id='discountproducts_list'}
        {elseif (isset($expired_warning) && $expired_warning|count_characters > 0)}
            <p class="alert alert-warning">{$expired_warning}</p>
        {/if}
    </div>
</section>
