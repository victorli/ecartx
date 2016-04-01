{$linkfont|html_entity_decode}
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
        background:{$maincolor};
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
        background:{$maincolor};
    {rdelim}

    .button.button-small span:hover,
    .button.button-medium:hover,
    .button.exclusive-medium span:hover,
    .button.exclusive-medium span:hover span {ldelim}
        background:{$maincolor};
    {rdelim}

    .button.ajax_add_to_cart_button:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    .button.ajax_add_to_cart_button:hover {ldelim}
        border-color:{$maincolor};
    {rdelim}

     .button.lnk_view:hover {ldelim}
        background:{$maincolor};
        border-color:{$maincolor};
    {rdelim}

     .footer_link .button.lnk_view.btn-default:hover {ldelim}
        background:{$maincolor};
    {rdelim}

     /* Breadcrumb */
     .breadcrumb a:hover {ldelim}
        color:{$maincolor};
    {rdelim}

    /* Navigation button*/
    .cart_navigation .button-exclusive:hover,
    .cart_navigation .button-exclusive:hover,
    .cart_navigation .button-exclusive:active {ldelim}
        background:{$maincolor};
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
        background-color:{$maincolor};
        border-color: {$maincolor};
    {rdelim}

    /* CMS module */
    #cms_pos .cms-toggle li a:hover {ldelim}
        color:{$maincolor};
    {rdelim}

    /* Block cart module */
    .cart_block .cart-info .product-name a:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    .cart_block .cart-buttons a#button_order_cart:hover span {ldelim}
        background:{$maincolor};
    {rdelim}


    /* Product list */
    ul.product_list li .product-name:hover {ldelim}
        color:{$maincolor};
    {rdelim}
    ul.product_list .button.ajax_add_to_cart_button:hover,
    ul.product_list .product-image-container .quick-view,
    ul.product_list.list .button.ajax_add_to_cart_button:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    ul.product_list .button.ajax_add_to_cart_button:hover,
    ul.product_list .functional-buttons div.compare a:hover,
    ul.product_list.list .button.ajax_add_to_cart_button:hover {ldelim}
        border-color: {$maincolor};
    {rdelim}
    ul.product_list .functional-buttons div a:hover,
    ul.product_list .functional-buttons div label:hover,
    ul.product_list .functional-buttons div.compare a:hover {ldelim}
        background:{$maincolor};
        border-color:{$maincolor};
    {rdelim}

    /* Blocklayered */
    .layered_price .layered_slider {ldelim}
        background:{$maincolor};
    {rdelim}

    /* Category page */
    .content_sortPagiBar .display li a:hover, .content_sortPagiBar .display_m li a:hover,
    .content_sortPagiBar .display li.selected a, .content_sortPagiBar .display_m li.selected a {ldelim}
        background-color:{$maincolor}!important;
    {rdelim}

    /* Product page */
    #product .primary_block .box-info-product label.label_radio:hover,
    #product .primary_block .box-info-product label.label_radio.checked,
    #thumbs_list li a:hover, #thumbs_list li a.shown,
    #view_scroll_left:hover, #view_scroll_right:hover {ldelim}
        border-color: {$maincolor};
    {rdelim}
    #product .btn.button-plus, .box-info-product .exclusive {ldelim}
        background:{$maincolor};
    {rdelim}
    #product #tab-container .nav-tabs > li.active > a,
    #product #tab-container .nav-tabs > li.active > a:hover,
    #product #tab-container .nav-tabs > li.active > a:focus,
    #product #tab-container .nav-tabs > li > a:hover,
    #product #tab-container .nav-tabs > li > a:focus {ldelim}
        color:{$maincolor};
        background-color:{$maincolor};
    {rdelim}
    .buttons_bottom_block #wishlist_button:hover, .box-info-product #add_to_compare:hover,
    .buttons_bottom_block #wishlist_button:before:hover, .box-info-product #add_to_compare:before:hover,
    #thumbs_list li a.shown:before, #view_scroll_left:hover:before, #view_scroll_right:hover:before {ldelim}
        color:{$maincolor};
    {rdelim}
    #nav_page a:hover {ldelim}
        background:{$maincolor};
        border-color: {$maincolor};
    {rdelim}

    /* About us page */
    #cms #row-middle .title_block_cms:after {ldelim}
        color:{$maincolor};
    {rdelim}

    #cms ul.social_cms li a:hover {ldelim}
        background:{$maincolor};
    {rdelim}
    #cms ul.social_cms li a:hover {ldelim}
        border-color:{$maincolor};
    {rdelim}

    /* Scroll to top */
    .scroll_top:hover {ldelim}
        background: {$maincolor};
    {rdelim}

    /* Title block font */
    .columns-container .block .title_block,
    .columns-container .block h4,
    .columns-container .block .title_block,
    .columns-container .block h4 {ldelim}
        font-family: {$fontname};
    {rdelim}

</style>