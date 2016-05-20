{if $newsletter_setting}
<div id="overlay" style="display: block;" onclick="closeDialog()"></div>
{if $newsletter_setting.background != ''}
<div class="ovicnewsletter" style="background-image: url({$newsletter_setting.background});">
{else}
<div class="ovicnewsletter">
{/if}
    <div class="inner">
        <div class="ovicnewsletter-close"><a href="javascript:void(0)" onclick="closeDialog()"><img src="{$ovicNewsletterUrl}/images/icon-close.png" /> </a></div>
        <div class="clearfix newsletter-content">
            {$newsletter_setting.content}
        </div>
        <div class="newsletter-form">
            <div id="regisNewsletterMessage"></div>
			<div class="" >
				<div class="clearfix">
					<input class="input-email" id="input-email" id="" type="text" name="email" size="18" placeholder="{l s='Enter your email...' mod='blocknewsletter'}" value="" />                    
					<a onclick="regisNewsletter()" name="submitNewsletter" class="btn btn-default button">{l s="Subscribe"}</a>
				</div>
                <div style="margin-top:15px">                    
                    <div class="checkbox" style="margin-bottom:0"><label><input id="persistent" name="persistent" type="checkbox" value="1">{l s="Do not show this popup again" mod="ovicnewsletter"}</label></div>
                </div>
				                    
			</div>
    		
        </div>
    </div>    
</div>
{addJsDefL name=regisNewsletterMessage}{l s='You have just subscribled successfully!' js=1}{/addJsDefL}
{addJsDefL name=enterEmail}{l s='Enter your email please!' js=1}{/addJsDefL}
{/if}
<script type="text/javascript">
    var ovicNewsletterUrl = "{$ovicNewsletterUrl}";
</script>