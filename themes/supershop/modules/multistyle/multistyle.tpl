{*$linkfont|html_entity_decode*}
<style type="text/css">
    /***  Font default ***/
    .mainFont{ldelim}
        font-family:{$fontname}!important;
    {rdelim}
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {ldelim}
        font-family: {$fontname};
    {rdelim}

    /*** Link color class ***/
    .linkcolor{ldelim}
        color:{$linkcolor}!important;
    {rdelim}
    .linkcolor:hover{ldelim}
        color:{$linkHovercolor}!important;
    {rdelim}

    /*** Button color class ***/
    .btnbgcolor{ldelim}
        color:{$btncolor}!important;
    {rdelim}
    .btnbgcolor:hover{ldelim}
        color:{$btnHovercolor}!important;
    {rdelim}

    /*** Main color class ***/
    .mainColor,.mainHoverColor,.mainColorHoverOnly:hover {ldelim}
        color:{$maincolor}!important;
    {rdelim}

    /*** Color hover ***/
    .mainHoverColor:hover{ldelim}
        color:{$mainhovercolor}!important;
    {rdelim}

    /*** background not change on hover ***/
    .mainBgColor,.mainBgHoverColor {ldelim}
        background-color:{$maincolor}!important;
    {rdelim}

    /*** background change on hover ***/
    .mainBgHoverColor:hover,.mainBgHoverOnly:hover{ldelim}
        background-color:{$mainhovercolor}!important;
    {rdelim}

    /*** border only hover ***/
    .mainBorderColor, .mainBorderHoverColor {ldelim}
        border-color:{$mainhovercolor}!important;
    {rdelim}
    .mainBorderLight, .mainBorderHoverColor:hover, .mainBorderHoverOnly:hover{ldelim}
        border-color:{$mainhovercolor}!important;
    {rdelim}
    dt.mainHoverColor:hover .product-name a{ldelim}
        color:{$maincolor};
    {rdelim}
    dt.mainHoverColor:hover .cart-images{ldelim}
        border-color:{$maincolor};
    {rdelim}

    /*******************************************/
    /**            ThemeStyle                 **/
    /*******************************************/

    /** Theme Button **/
    
    .button.button-small {ldelim}
        background:{$btncolor};
    {rdelim}

    .button.button-medium,
    .button.button-small,
    .button.exclusive-medium,
    .button.exclusive-small {ldelim}
        color:{$btntextcolor};
    {rdelim}
    
    .button.button-medium:hover,
    .button.button-small:hover,
    .button.exclusive-medium:hover,
    .button.exclusive-small:hover {ldelim}
        color:{$btntextHovercolor};
    {rdelim}

    input.button_mini:hover,
    input.button_small:hover,
    input.button:hover,
    input.button_large:hover,
    input.exclusive_mini:hover,
    input.exclusive_small:hover,
    input.exclusive:hover,
    input.exclusive_large:hover,
    a.button_mini:hover,
    a.button_small:hover,
    a.button:hover,
    a.button_large:hover,
    a.exclusive_mini:hover,
    a.exclusive_small:hover,
    a.exclusive:hover,
    a.exclusive_large:hover {ldelim}
        background:{$btnHovercolor};
    {rdelim}

    input.button_mini:active,
    input.button_small:active,
    input.button:active,
    input.button_large:active,
    input.exclusive_mini:active,
    input.exclusive_small:active,
    input.exclusive:active,
    input.exclusive_large:active,
    a.button_mini:active,
    a.button_small:active,
    a.button:active,
    a.button_large:active,
    a.exclusive_mini:active,
    a.exclusive_small:active,
    a.exclusive:active,
    a.exclusive_large:active {ldelim}
        background:{$btnHovercolor};
    {rdelim}

    .button.button-small span:hover,
    .button.button-medium:hover,
    .button.exclusive-medium span:hover,
    .button.exclusive-medium span:hover span {ldelim}
        background:{$btnHovercolor};
    {rdelim}

    .button.ajax_add_to_cart_button:hover {ldelim}
        background:{$btnHovercolor};
    {rdelim}
    .button.ajax_add_to_cart_button:hover {ldelim}
        border-color:{$btnHovercolor};
    {rdelim}

     .button.lnk_view:hover {ldelim}
        background:{$btnHovercolor};
        border-color:{$btnHovercolor};
    {rdelim}

     .footer_link .button.lnk_view.btn-default:hover {ldelim}
        background:{$btnHovercolor};
    {rdelim}

     /* Breadcrumb */
     .breadcrumb a:hover {ldelim}
        color:{$maincolor};
    {rdelim}

    /* Navigation button*/
    .cart_navigation .button-exclusive:hover,
    .cart_navigation .button-exclusive:hover,
    .cart_navigation .button-exclusive:active {ldelim}
        background:{$btnHovercolor};
    {rdelim}

    /* Header */
    header .nav #text_top a {ldelim}
        color:{$maincolor};
    {rdelim}
    header .row .shopping_cart > a:first-child:before {ldelim}
        background-color:{$maincolor};
    {rdelim}

     /* OWL button */
     .owl-buttons div:hover {ldelim}
        background-color:{$btnHovercolor};
        border-color: {$btnHovercolor};
    {rdelim}
    #best-sellers_block_right .owl-prev:hover, 
    #best-sellers_block_right .owl-next:hover {ldelim}
        background-color:{$btnHovercolor};
        border-color: {$btnHovercolor};
    {rdelim}

    /* CMS module */
    /*
    #cms_pos .header-toggle li a,
    #cms_pos .cms-toggle li a, 
    .header-toggle a, 
    .currencies_ul li a, 
    .languages-block_ul li span {ldelim}
        color:{$linkcolor}!important;
    {rdelim}
    
    #cms_pos .header-toggle li a:hover,
    #cms_pos .cms-toggle li a:hover,
    .header-toggle a:hover, 
    .currencies_ul li a:hover, 
    .languages-block_ul li:hover span {ldelim}
        color:{$linkHovercolor}!important;
    {rdelim}
    */

    /* Advanced topmenu module */
    #nav_topmenu ul.nav > li.active > a,
    #nav_topmenu ul.nav > li > a:hover,
    #nav_topmenu ul.nav > li.open > a {ldelim}
        color:{$maincolor};
        background-color:{$maincolor};
    {rdelim}
    #nav_topmenu ul.nav > li.active.dropdown > a:after,
    #nav_topmenu ul.nav > li.dropdown > a:hover:after,
    #nav_topmenu ul.nav > li.dropdown.open > a:after {ldelim}
        color:{$maincolor};
    {rdelim}
    #nav_topmenu ul.nav .list ul.block li.level-2:hover {ldelim}
        background:{$maincolor};
    {rdelim}

    /* Block cart module */
    .shopping_cart span.ajax_cart_total,
    .cart_block .cart-info .product-name a:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .cart_block .cart-buttons a#button_order_cart span {ldelim}
        background:{$maincolor};
    {rdelim}
    .cart_block .cart-buttons a#button_order_cart span {ldelim}
        color:{$btntextcolor};
    {rdelim}
    .cart_block .cart-buttons a#button_order_cart:hover span {ldelim}
        color:{$btntextHovercolor};
    {rdelim}
    #layer_cart .layer_cart_cart .button-container span.exclusive-medium i {ldelim}
        color:{$btntextcolor};
    {rdelim}
    #layer_cart .layer_cart_cart .button-container span.exclusive-medium:hover i {ldelim}
        color:{$btntextHovercolor};
    {rdelim}
    
    /* Module: Vertical megamenus */
    .vertical-megamenus h4.title {ldelim}
        background:{$maincolor};
    {rdelim}
    
    /* Module: Blog */
    #submitComment:hover{ldelim}
        background:{$btnHovercolor};
    {rdelim}
    
    /* Module: Tabs 3 module on home page */
     .owl-nav .owl-next:hover, .owl-nav .owl-prev:hover,
    .tab-content .owl-carousel .owl-controls .owl-nav .owl-next:hover, 
    .tab-content .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
    .option5 .tab-content .owl-carousel .owl-controls .owl-nav .owl-next:hover, 
    .option5 .tab-content .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
    .option2 .tab-content .owl-carousel .owl-controls .owl-nav .owl-next:hover, 
    .option2 .tab-content .owl-carousel .owl-controls .owl-nav .owl-prev:hover {ldelim}
        background:{$btnHovercolor};
    {rdelim}
    
    #home-popular-tabs > li.active, #home-popular-tabs > li.active:hover, #home-popular-tabs > li:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    .owl-carousel .owl-controls .owl-nav .owl-next:hover, .owl-carousel .owl-controls .owl-nav .owl-prev:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    
    /* Module: Homeslider */
    #homepage-slider .bx-wrapper .bx-controls-direction a:hover:before {ldelim}
        background:{$maincolor};
    {rdelim}
    #layer_cart .button.exclusive-medium span:hover, #layer_cart .button.exclusive-medium span.mainBgHoverColor:hover {ldelim}
        background:{$btnHovercolor}!important;
    {rdelim}
    
    /* Module: Discount product - Deal of the day */
    h2.heading-title .coundown-title i.icon-time {ldelim}
        color:{$maincolor};
    {rdelim}
    #discountproducts_list .owl-nav .owl-next:hover, 
    #discountproducts_list .owl-nav .owl-prev:hover {ldelim}
        background:{$maincolor};
    {rdelim} 

    /* Module: Block html */
    #blockhtml_displayTopColumn h1 i,
    h1.heading-title .coundown-title i.icon-time {ldelim}
        color:{$maincolor};
    {rdelim}

    /* Module: Home category */
    .home-category .nav-tabs > li.active > a,.home-category .nav-tabs > li.active > a:hover,
    .home-category .nav-tabs > li.active > a:focus,
    .home-category .nav-tabs > li > a:hover,.home-category .nav-tabs > li > a:focus {ldelim}
        color:{$maincolor};
        background-color:{$maincolor};
    {rdelim}

    /* Module: Testimonial */
    #testimonial_block .block_testimonial_name {ldelim}
        color:{$maincolor};
    {rdelim}

    /* Module: Brand slide */
    #brands_slider .brands_slide_wrapper, #brands_slider .brands_list_wrapper {ldelim}
        background:{$grbacolor};
    {rdelim}

    /*  */
    #footer #advancefooter #newsletter_block_left .form-group .button-small span {ldelim}
        color: {$btntextcolor};
    {rdelim}
    .footer-container #footer #advancefooter #block_contact_infos > div ul li i {ldelim}
        color: {$maincolor};
    {rdelim}
    .footer-container {ldelim}
        border-top: 1px solid {$maincolor};
    {rdelim}
    
    /* Product list */
    .option2 ul.product_list li .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    ul.product_list .button.ajax_add_to_cart_button,
    .option2 .functional-buttons .button.ajax_add_to_cart_button,
    .option2 .flexible-custom-groups ul li.active, 
    .option2 .flexible-custom-groups ul li:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    
    .option5 ul.product_list li .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    ul.product_list .button.ajax_add_to_cart_button,
    .option5 .functional-buttons .button.ajax_add_to_cart_button,
    .option5 .flexible-custom-groups ul li.active, 
    .option5 .flexible-custom-groups ul li:hover {ldelim}
        background:{$maincolor};
    {rdelim}
 
    
    ul.product_list.grid > li .product-container .functional-buttons .quick-view:hover, 
    ul.product_list.grid > li .product-container .functional-buttons .quick-view:hover i,
    ul.product_list .functional-buttons div a:hover, 
    ul.product_list .functional-buttons div label:hover, 
    ul.product_list .functional-buttons div.compare a:hover {ldelim}
        color:{$maincolor}!important;
    {rdelim}
    ul.product_list.list .functional-buttons a.quick-view:hover, 
    ul.product_list.list .functional-buttons div.compare a:hover, 
    ul.product_list.list .functional-buttons div.wishlist a:hover {ldelim}
        border-color:{$maincolor};
        background:{$maincolor};
    {rdelim}
    
    ul.product_list .button.ajax_add_to_cart_button:hover,
    ul.product_list .functional-buttons div.compare a:hover,
    ul.product_list.list .button.ajax_add_to_cart_button:hover {ldelim}
        /* border-color: {$btnHovercolor}; */
    {rdelim}

    /* Blocklayered */
    .layered_price .layered_slider,
    .layered_price .ui-slider-horizontal .ui-slider-range {ldelim}
        background:{$maincolor};
    {rdelim}
    .layered_price .ui-state-default, 
    .layered_price .ui-widget-content .ui-state-default, 
    .layered_price .ui-widget-header .ui-state-default {ldelim}
        background:{$maincolor};
    {rdelim}

    /* Page: Category */
    #subcategories ul li a:hover {ldelim}
        background:{$maincolor};
        border: 1px solid {$maincolor};
    {rdelim}
    .content_sortPagiBar .display li.selected a, 
    .content_sortPagiBar .display_m li.selected a, 
    .display li.selected a, .display_m li.selected a,
    .content_sortPagiBar .display li a:hover, 
    .content_sortPagiBar .display_m li a:hover, 
    .display li a:hover, .display_m li a:hover {ldelim}
        background-color:{$maincolor};
    {rdelim}
    .button.button-medium.bt_compare {ldelim}
        background:{$maincolor};
    {rdelim}
    
    .pagination > li.pagination_next > a:hover, 
    .pagination > li.pagination_next > a:hover, 
    .pagination > li.pagination_next > span:hover, 
    .pagination > li.pagination_next > span:hover, 
    .pagination > li.pagination_previous > a:hover, 
    .pagination > li.pagination_previous > a:hover, 
    .pagination > li.pagination_previous > span:hover, 
    .pagination > li.pagination_previous > span:hover {ldelim}
        color: {$maincolor};
    {rdelim}
    .pagination > .active > a, 
    .pagination > .active > a:hover, 
    .pagination > .active > a:hover, 
    .pagination > .active > span, 
    .pagination > .active > span:hover, 
    .pagination > .active > span:hover {ldelim}
        color: {$maincolor};
    {rdelim}

    /* Page: Product */
    #product .primary_block .box-info-product label.label_radio:hover,
    #product .primary_block .box-info-product label.label_radio.checked,
    #thumbs_list li a:hover, #thumbs_list li a.shown {ldelim}
        border-color: {$maincolor};
    {rdelim}
    #view_scroll_left:hover:before, #view_scroll_right:hover:before {ldelim}
        background: {$btnHovercolor};
        border-color: {$maincolor};
    {rdelim}
    .buttons_bottom_block #wishlist_button:hover, .box-info-product #add_to_compare:hover,
    .buttons_bottom_block #wishlist_button:before:hover, .box-info-product #add_to_compare:before:hover,
    #thumbs_list li a.shown:before {ldelim}
        color:{$maincolor};
    {rdelim}
    #nav_page a:hover {ldelim}
        background:{$maincolor};
        border-color: {$maincolor};
    {rdelim}
    
    .box-info-product .exclusive {ldelim}
        background:{$maincolor};
    {rdelim}
    
    #box-product #size_chart:hover,
    #usefull_link_block li a:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    
    /* Module: Block Search */
    .ac_results li.ac_over {ldelim}
        background: {$maincolor}!important;
    {rdelim}
    
    /* Module: Product category */
    .blockproductscategory a#productscategory_scroll_left:hover, 
    .blockproductscategory a#productscategory_scroll_right:hover {ldelim}
        border-color: {$btnHovercolor};
        background: {$btnHovercolor};
    {rdelim}

    /* Page: About us */
    #cms #row-middle .title_block_cms:after {ldelim}
        color:{$maincolor};
    {rdelim}

    #cms ul.social_cms li a:hover {ldelim}
        background:{$btnHovercolor};
    {rdelim}
    #cms ul.social_cms li a:hover {ldelim}
        border-color:{$btnHovercolor};
    {rdelim} 
    
    /* Scroll to top */
    .scroll_top:hover {ldelim}
        background: {$btnHovercolor};
    {rdelim}
    
    /* Title block font */
    .columns-container .block .title_block,
    .columns-container .block h4 {ldelim}
        background: {$maincolor};
    {rdelim}
    .columns-container .block .title_block,
    .columns-container .block h4 {ldelim}
        font-family: {$fontname};
    {rdelim}
    
     /* Footer links */
    #footer #advancefooter #footer_row2 ul.bullet li:hover,
    .footer-container #footer #advancefooter ul li a:hover,
    .footer-container #footer #advancefooter #tags_block_footer a:hover {ldelim}
        color: {$linkHovercolor};
    {rdelim}
    
/*******************************************************
** Option1 Second Color **
********************************************************/
    /* Product List Option1 */
    .option1 ul.product_list li .product-name:hover {ldelim}
    	color: {$option1Secondcolor};
    {rdelim}  
    .option1 ul.product_list .button.ajax_add_to_cart_button:hover {ldelim}
    	background: {$option1Secondcolor};
    {rdelim}  
    
    /* OWL Button Option1 */
    .option1 #best-sellers_block_right .owl-prev:hover, 
    .option1 #best-sellers_block_right .owl-next:hover {ldelim}
        background-color:{$maincolor};
        border-color: {$maincolor};
    {rdelim}
    .option1 .button.button-medium.bt_compare:hover{
        background: {$option1Secondcolor};
    }
    /* Module: Mega Top Menu Option1 */
    @media (min-width: 768px) {ldelim}
        .option1 #topmenu {ldelim}
        	background: {$option1Secondcolor};
        {rdelim}   
    {rdelim}
    
    .option1 #nav_top_links a:hover {ldelim}
        color:{$option1Secondcolor};
    {rdelim}
    .option1 #nav_topmenu ul.nav > li.active:first-child a {ldelim}
        /* background-color: {$option1Secondcolor}; */
    {rdelim}
    
    /* Module: Vertical megamenus Option1 */
    .option1 .vertical-megamenus span.new-price {ldelim}
        color: {$option1Secondcolor};
    {rdelim}
    .option1 .mega-group-header span {ldelim}
        border-left: 3px solid {$option1Secondcolor};
    {rdelim}
    @media (min-width: 768px) {ldelim}
        .option1 .vertical-megamenus ul.megamenus-ul li:hover {ldelim}
            border-left: 3px solid {$option1Secondcolor};
        {rdelim}
    {rdelim}
    @media (max-width: 767px) {ldelim}
        .option1 .vertical-megamenus li.dropdown.open {ldelim}
            background:{$option1Secondcolor};
        {rdelim}
    {rdelim}
    .option1 .vertical-megamenus ul.megamenus-ul li.active {ldelim}
    	border-left: 3px solid {$option1Secondcolor};
    {rdelim}
    
    /* Module: Block search Option1 */
    .option1 #search_block_top .btn.button-search {ldelim}
        background: {$option1Secondcolor};
    {rdelim}
    
    /* Module: Newsletter Option1 */
    .option1 #footer #advancefooter #newsletter_block_left .form-group .button-small {ldelim}
        background: {$option1Secondcolor};
    {rdelim}
    
    /* Module: Block cart Option1 */
    .option1 .cart_block .cart-buttons a span {ldelim}
        background: {$option1Secondcolor};
    {rdelim}
    
    /* Menuontop option1 */
    .option1 #nav_topmenu.menuontop {ldelim}
        background: {$option1Secondcolor};
    {rdelim}
    
/*******************************************************
** Option2 Color **
********************************************************/
      
    /* Header Option2 */
    .option2 #page #header {ldelim}
        background: {$option2Secondcolor};
    {rdelim}
    
    /* Product List Option2 */
    .option2 ul.product_list.grid > li .product-container .price.product-price,
    .option2 ul.product_list li .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .option2 .functional-buttons .button.ajax_add_to_cart_button,
    .option2 .flexible-custom-groups ul li.active, 
    .option2 .flexible-custom-groups ul li:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    .option2 ul.product_list .button.ajax_add_to_cart_button:hover,
    .option2 .functional-buttons .button.ajax_add_to_cart_button:hover {ldelim}
    	background: {$option2Secondcolor};
    {rdelim}
    
    /* Module: Flexible Brand Option2 */
    .option2 .flexible-brand-groups .module-title,
    .option2 .button-medium.bt_compare:hover {ldelim}
        background:{$option2Secondcolor};
    {rdelim}
    .option2 .flexible-brand-list li:hover a, 
    .option2 .flexible-brand-list li.active a {ldelim}
        border-left-color: {$maincolor};
        color:{$maincolor};
    {rdelim}
    .flexible-custom-products .product-name:hover,
    .flexible-custom-products .content_price .price.product-price,
    .flexible-brand-products .content_price .price.product-price,
    .option2 .flexible-brand-products .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .option2 .flexible-custom-products .functional-buttons a.quick-view:hover, 
    .option2 .flexible-custom-products .functional-buttons div a:hover,
    .option2 .flexible-brand-products .functional-buttons a.quick-view:hover, 
    .option2 .flexible-brand-products .functional-buttons div a:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    
    /* Module: Vertical megamenus Option2 */
    .option2 .vertical-megamenus span.new-price {ldelim}
        color: {$option2Secondcolor};
    {rdelim}
    .option2 .mega-group-header span {ldelim}
        border-left: 3px solid {$option2Secondcolor};
    {rdelim}
    @media (min-width: 768px) {ldelim}
        .option2 .vertical-megamenus ul.megamenus-ul li:hover {ldelim}
            border-left: 3px solid {$option2Secondcolor};
        {rdelim}
    {rdelim}
    @media (max-width: 767px) {ldelim}
        .option2 .vertical-megamenus li.dropdown.open {ldelim}
            background:{$option2Secondcolor};
        {rdelim}
    {rdelim}
    .option2 .vertical-megamenus ul.megamenus-ul li.active {ldelim}
    	border-left: 3px solid {$option2Secondcolor};
    {rdelim}
    
    /* Module: Newsletter Option2 */
    .option2 #footer #advancefooter #newsletter_block_left .form-group .button-small {ldelim}
        background: {$option2Secondcolor};
    {rdelim}
    
    /* Module: Block cart Option2 */
    .option2 header .shopping_cart span.ajax_cart_quantity {ldelim}
        background: {$maincolor};
    {rdelim}
    .option2 .cart_block .cart-buttons a span {ldelim}
        background: {$option2Secondcolor};
    {rdelim}
    
    /* OWL Nav Option2 */
    .option2 .owl_wrap .owl-controls .owl-nav .owl-next:hover, 
    .option2 .owl_wrap .owl-controls .owl-nav .owl-prev:hover {ldelim}
        background: {$btnHovercolor};
    {rdelim}
    
    /* Module: Block Search Option2*/
    .option2 #search_block_top .btn.button-search {ldelim}
        background: {$maincolor};
    {rdelim}
    
    /* Module: Block User Info Option2 */
    .option2 header #currencies-block-top div.current:hover:after, 
    .option2 header #languages-block-top div.current:hover:after,
    .option2 header .header_user_info a.header-toggle-call:hover:after {ldelim}
        color: {$maincolor};
    {rdelim}
    .option2 #nav_topmenu.menuontop,    
    .option2 #nav_topmenu.menuontop #topmenu {ldelim}
        background: {$option2Secondcolor};
    {rdelim}
    
    
/*******************************************************
** Option3 Second Color **
********************************************************/
    /* Header option3 */
    .option3 #page #header {ldelim}
        background: {$option3Secondcolor};
    {rdelim}
    
    /* Module: Mega Menu Top Header */
    .option3 #nav_topmenu ul.nav > li.mega_menu_item > a:hover {ldelim}
        background-color:{$maincolor};
    {rdelim}
    @media (max-width: 767px) {ldelim}
        .option3 #nav_topmenu .navbar-header {ldelim}
            background:{$maincolor};    
        {rdelim}
    {rdelim}    
    
    /* Module: Search with image */
    .option3 #search_block_top, .option3 #search_block_top #search_query_top, .option3 #call_search_block:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    
    
    /* Module: Newsletter */
    .option3 #footer #advancefooter #newsletter_block_left .form-group .button-small {ldelim}
        background: {$option3Secondcolor};
    {rdelim}
    
    /* Module: Block cart  */
    .option3 header#header .shopping_cart,
    .option3 header .row .shopping_cart > a:first-child,
    .option3 .cart_block .cart-buttons a span {ldelim}
        background: {$option3Secondcolor};
    {rdelim}
    .option3 header .row .shopping_cart > a:first-child:before {ldelim}
        background-color: {$option3Secondcolor};
    {rdelim}
    .option3 header .shopping_cart span.ajax_cart_quantity {ldelim}
        background: {$maincolor};
    {rdelim}
    
    /* Module: Slideshow option3 */
    .option3 .displayHomeSlider .tp-rightarrow.default:hover:before, 
    .option3 .displayHomeSlider .tp-rightarrow:hover:before, 
    .option3 .displayHomeSlider .tp-leftarrow.default:hover:before, 
    .option3 .displayHomeSlider .tp-leftarrow:hover:before {ldelim}
        background: {$maincolor};
    {rdelim}
    
/*******************************************************
** Option4 Second Color **
********************************************************/
 /* Product List option4 */
    .option4 ul.product_list li .product-name:hover {ldelim}
    	color: {$option4Secondcolor};
    {rdelim}  
    .option4 ul.product_list .button.ajax_add_to_cart_button:hover {ldelim}
    	background: {$option4Secondcolor};
    {rdelim}  
    /* Module: Mega Top Menu option4 */
    @media (min-width: 768px) {ldelim}
        .option4 .main-top-menus,
        .option4 #topmenu {ldelim}
        	background: {$option4Secondcolor};
        {rdelim}    
    {rdelim}
    
    .option4 #nav_top_links a:hover {ldelim}
        color:{$option4Secondcolor};
    {rdelim}
    .option4 #nav_topmenu ul.nav > li.level-1.active:first-child a {ldelim}
        /* background-color: {$option4Secondcolor}; */
    {rdelim}
    
    /* Module: Vertical megamenus option4 */
    .option4 .vertical-megamenus span.new-price {ldelim}
        color: {$option4Secondcolor};
    {rdelim}
    .option4 .mega-group-header span {ldelim}
        border-left: 3px solid {$option4Secondcolor};
    {rdelim}
    @media (min-width: 768px) {ldelim}
        .option4 .vertical-megamenus ul.megamenus-ul li:hover {ldelim}
            border-left: 3px solid {$option4Secondcolor};
        {rdelim}
    {rdelim}
    @media (max-width: 767px) {ldelim}
        .option4 .vertical-megamenus li.dropdown.open {ldelim}
            background:{$option4Secondcolor};
        {rdelim}
    {rdelim}
    .option4 .vertical-megamenus ul.megamenus-ul li.active {ldelim}
    	border-left: 3px solid {$option4Secondcolor};
    {rdelim}
    
    /* Module: Block search option4 */
    .option4 #search_block_top .btn.button-search {ldelim}
        background: {$option4Secondcolor};
    {rdelim}
    
    /* Module: Newsletter option4 */
    .option4 #footer #advancefooter #newsletter_block_left .form-group .button-small {ldelim}
        background: {$option4Secondcolor};
    {rdelim}
    
    /* Module: Block cart option4 */
    .option4 .cart_block .cart-buttons a span {ldelim}
        background: {$option4Secondcolor};
    {rdelim}
 /*******************************************************
** Option5 Color **
********************************************************/
      
    
    /* Product List option5 */
    .option5 ul.product_list.grid > li .product-container .price.product-price,
    .option5 ul.product_list li .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .option5 .functional-buttons .button.ajax_add_to_cart_button,
    .option5 .flexible-custom-groups ul li.active, 
    .option5 .flexible-custom-groups ul li:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    .option5 ul.product_list .button.ajax_add_to_cart_button:hover,
    .option5 .functional-buttons .button.ajax_add_to_cart_button:hover {ldelim}
    	background: {$option5Secondcolor};
    {rdelim}
    
    /* Module: Flexible Brand option5 */
    .option5 .flexible-brand-groups .module-title,
    .option5 .button-medium.bt_compare:hover {ldelim}
        background:{$option5Secondcolor};
    {rdelim}
    .option5 .flexible-brand-list li:hover a, 
    .option5 .flexible-brand-list li.active a {ldelim}
        border-left-color: {$maincolor};
        color:{$maincolor};
    {rdelim}
    .flexible-custom-products .product-name:hover,
    .flexible-custom-products .content_price .price.product-price,
    .flexible-brand-products .content_price .price.product-price,
    .option5 .flexible-brand-products .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .option5 .flexible-custom-products .functional-buttons a.quick-view:hover, 
    .option5 .flexible-custom-products .functional-buttons div a:hover,
    .option5 .flexible-brand-products .functional-buttons a.quick-view:hover, 
    .option5 .flexible-brand-products .functional-buttons div a:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    
    /* Module: Vertical megamenus option5 */
    .option5 .vertical-megamenus span.new-price {ldelim}
        color: {$option5Secondcolor};
    {rdelim}
    .option5 .mega-group-header span {ldelim}
        border-left: 3px solid {$option5Secondcolor};
    {rdelim}
    @media (min-width: 768px) {ldelim}
        .option5 .vertical-megamenus ul.megamenus-ul li:hover {ldelim}
            border-left: 3px solid {$option5Secondcolor};
        {rdelim}
    {rdelim}
    @media (max-width: 767px) {ldelim}
        .option5 .vertical-megamenus li.dropdown.open {ldelim}
            background:{$option5Secondcolor};
        {rdelim}
    {rdelim}
    .option5 .vertical-megamenus ul.megamenus-ul li.active {ldelim}
    	border-left: 3px solid {$option5Secondcolor};
    {rdelim}
    
    /* Module: Newsletter option5 */
    .option5 #footer #advancefooter #newsletter_block_left .form-group .button-small {ldelim}
        background: {$option5Secondcolor};
    {rdelim}
    
    /* Module: Block cart option5 */
    .option5 .cart_block .cart-buttons a span {ldelim}
        background: {$option5Secondcolor};
    {rdelim}
    
    /* OWL Nav option5 */
    .option5 .owl_wrap .owl-controls .owl-nav .owl-next:hover, 
    .option5 .owl_wrap .owl-controls .owl-nav .owl-prev:hover {ldelim}
        background: {$btnHovercolor};
    {rdelim}
    
    /* Module: Block Search option5*/
    .option5 #search_block_top .btn.button-search {ldelim}
        background: {$maincolor};
    {rdelim}
    
    /* Module: Block User Info option5 */
    .option5 header #currencies-block-top div.current:hover:after, 
    .option5 header #languages-block-top div.current:hover:after,
    .option5 header .header_user_info a.header-toggle-call:hover:after {ldelim}
        color: {$maincolor};
    {rdelim}
    
    @media (min-width: 768px) {ldelim}
        .option5 #topmenu {ldelim}
        	background: {$option5Secondcolor};
        {rdelim}   
    {rdelim}
    
    .option5 #nav_top_links a:hover {ldelim}
        color:{$option5Secondcolor};
    {rdelim}
    .option5 #nav_topmenu ul.nav > li.active:first-child a {ldelim}
        /* background-color: {$option5Secondcolor}; */
    {rdelim}
    
    /* Menuontop option1 */
    .option5 #nav_topmenu.menuontop {ldelim}
        background: {$option5Secondcolor};
    {rdelim}

</style>