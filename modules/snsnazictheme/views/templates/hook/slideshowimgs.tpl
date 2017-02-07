{assign var="eq" value=0|rand:100000|cat:$smarty.now}
{if isset($imgs) && $imgs}
	<div class="container">
		<div id="sns_imgslide_{$eq}" class="sns-slideimgs">
			<div class="loading"></div>
			<div class="navslider">
				<a class="prev" title="Prev" href="#"> </a>
				<a class="next" title="Next" href="#"> </a>
			</div>
			<div class="slideimgs preload">
				{foreach from=$imgs item=img}
					<div class="item">
						<a href="{$img.link}" title="{$img.title}">
							<img src="{$img.img}" alt="{$img.title}">
						</a>
					</div>
				{/foreach}
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(window).load(function(){
			var owl = $('#sns_imgslide_{$eq} .slideimgs');
			var owl_options = {
				responsiveBaseElement: '.sns-slideimgs',
			    items:1,
			    margin:0,
			    loop:true,
			    nav: false,
			    dots: true,
			    stagePadding:0,
			    smartSpeed:450,
			    animateIn: '{$animateIn}',
			    animateOut: '{$animateOut}',
			    {if $auto}
			    autoplay: false,
			    autoplayTimeout: {$auto},
			    autoplayHoverPause: true,	
			    {/if}
			};
			owl.owlCarousel(owl_options);

			$('#sns_imgslide_{$eq} .navslider .prev').on('click', function(e){
				e.preventDefault();
				owl.trigger('prev.owl.carousel');
			});
			$('#sns_imgslide_{$eq} .navslider .next').on('click', function(e){
				e.preventDefault();
				owl.trigger('next.owl.carousel');
			});

			$('#sns_imgslide_{$eq} .loading').fadeOut();
			
			owl.removeClass('preload');
			$('[name="SNS_NAZCP_LAYOUTTYPE"]').on('change', function(){
				// destroy carousel
				owl.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
				owl.find('.owl-stage-outer').children().unwrap();
				// reinitialize carousel
				owl.owlCarousel(owl_options);
			});
		});
	</script>
{/if}
