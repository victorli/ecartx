{if isset($view_data) AND !empty($view_data)}
{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
<!--Blogs slide-->
{if $current_option == 3}
<h2 class="title_block_option2 blog">{l s='NEW IN TODAY' mod='smartbloghomelatestnews'}</h2>
{elseif $current_option == 5}
<h2 class="title_block_option5 blog">{l s='NEW IN TODAY' mod='smartbloghomelatestnews'}</h2>
{else}
<h2 class="title_block_option2 blog">{l s='THE LATEST POSTS FROM OUR BLOG' mod='smartbloghomelatestnews'}</h2>
{/if}

<div id="wrap_blog">
    <div id="home_blog" class="owl_wrap">
    {foreach from=$view_data item=post}
        {assign var="options" value=null}
        {$options.id_post = $post.id}
        {$options.slug = $post.link_rewrite}
        <div class="slide_item block-content" >
            <div class="wrapper" >
                <div class="content">
                    <div class="blog-img">
                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                            <img src="{$modules_dir}smartblog/images/{$post.post_img}-home-default.jpg" alt="" />
                            <i class="fa fa-link"></i>
                        </a>
                    </div>
                    
                    <h3 class="content-title"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h3>
                    <p class="post_by_info">
                        {*}{l s='post by:' mod='smartbloghomelatestnews'}&nbsp;{$post.firstname}&nbsp;{$post.lastname}&nbsp;/&nbsp;{*}
                        <i class="fa fa-calendar-o"></i>&nbsp;{$post.date_added|date_format}&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-comments-o"></i>&nbsp;0&nbsp;{l s='Comments' mod='smartbloghomelatestnews'}
                    </p>
                    <p class="post_content_blog">
                        {$post.short_description|escape:'htmlall':'UTF-8'|truncate:125:"..."}
                    </p>
                    <p class="readmore">
                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{l s='Read more' mod='smartbloghomelatestnews'}</a>
                    </p>
                </div>
            </div>
        </div>
    {/foreach}
    </div>
{/if}
</div>
