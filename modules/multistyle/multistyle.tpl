{$linkfont|html_entity_decode}
<style type="text/css">
    .mainFont{ldelim}
        font-family:{$fontname}!important;
    {rdelim}
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: {$fontname};
    }
    /***** link color **********/
    .linkcolor{ldelim}
        color:{$linkcolor}!important;
    {rdelim}
    .linkcolor:hover{ldelim}
        color:{$linkHovercolor}!important;
    {rdelim}
    /****** button color ********/
    .btnbgcolor{ldelim}
        color:{$btncolor}!important;
    {rdelim}
    .btnbgcolor:hover{ldelim}
        color:{$btnHovercolor}!important;
    {rdelim}
    .mainColor,.mainHoverColor,.mainColorHoverOnly:hover {ldelim}
        color:{$maincolor}!important;
    {rdelim}
    /**
     * color change on hover
     */
    .mainHoverColor:hover{ldelim}
        color:{$mainhovercolor}!important;
    {rdelim}
    /**
     * background not change on hover
     */
    .mainBgColor,.mainBgHoverColor {ldelim}
        background-color:{$maincolor}!important;
    {rdelim}
    /**
     * background change on hover
     */
    .mainBgHoverColor:hover,.mainBgHoverOnly:hover{ldelim}
        background-color:{$mainhovercolor}!important;
    {rdelim}

    /**
     * border only hover
     */
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
    .ac_results li.ac_over{ldelim}
        background-color:{$maincolor}!important;
    {rdelim}
</style>