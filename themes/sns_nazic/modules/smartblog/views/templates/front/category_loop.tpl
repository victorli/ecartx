


<div itemtype="#" itemscope="" class="sdsarticleCat clearfix catlistitem">
    <div id="smartblogpost-{$post.id_post}">
		<div class="blog-articleContent">
			{assign var="options" value=null}
			{$options.id_post = $post.id_post} 
			{$options.slug = $post.link_rewrite}
			<a itemprop="url" title="{$post.meta_title}" class="blog-img" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>
				{assign var="activeimgincat" value='0'}
				{$activeimgincat = $smartshownoimg} 
				{if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
					<img itemprop="image" alt="{$post.meta_title}" src="{$modules_dir}smartblog/images/{$post.post_img}-single-default.jpg" class="imageFeatured">
				{/if}
			</a>
			<div class="sdsarticle-date">
				<span class="month">{$post.created|date_format:"%b"}</span>
				<span class="date">{$post.created|date_format:"%d"}</span>
			</div>

			<div class=" sdsarticle-title">
				<a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>
					{$post.meta_title}
				</a>
			</div>
			<div class="info">
				{assign var="options" value=null}
				{$options.id_post = $post.id_post}
				{$options.slug = $post.link_rewrite}
				{assign var="catlink" value=null}
				{$catlink.id_category = $post.id_category}
				{$catlink.slug = $post.cat_link_rewrite}
				<span>
					{if $smartshowauthor ==1}
						<span itemprop="author">
						&nbsp;<i class="fa fa-pencil-square-o"></i>&nbsp; 
						{if $smartshowauthorstyle != 0}
							{$post.firstname} {$post.lastname}
						{else}
							{$post.lastname} {$post.firstname}
						{/if}
						</span>
					{/if}
					 &nbsp;&nbsp;<i class="fa fa-tags"></i>&nbsp; 
					 <span itemprop="articleSection">
				 		 <a href="{smartblog::GetSmartBlogLink('smartblog_category',$catlink)}">
				     		{if $title_category != ''}
				     			{$title_category}
				     		{else}
				     			{$post.cat_name}
				     		{/if}
				 		</a>
					</span> &nbsp;
					<span class="comment"> &nbsp;
						<i class="fa fa-comments"></i>&nbsp; 
						<a title="{$post.totalcomment} Comments" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}#articleComments">
							{$post.totalcomment} {l s=' Comments' mod='smartblog'}
						</a>
					</span>
					{if $smartshowviewed ==1}
						&nbsp; <i class="fa fa-eye"></i>{l s=' Views' mod='smartblog'} ({$post.viewed})
					{/if}
				</span>
			</div>
			<div class="sdsarticle-des">
				<span itemprop="description" class="blog-desc">
					{$post.short_description}{l s="..."}
					{assign var="options" value=null}
					{$options.id_post = $post.id_post}  
					{$options.slug = $post.link_rewrite}  
					<a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="readmore">
						{l s='Read more' mod='smartblog'}
					</a>
				</span>
			</div>	
		</div>
	</div>
</div>