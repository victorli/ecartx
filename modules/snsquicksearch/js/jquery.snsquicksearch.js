$(document).ready(function()
{
    $('#search_block_top').on('click', function(e)
    {
        if ($(this).hasClass('snsquicksearch-open')){
            $(this).removeClass('snsquicksearch-open');
        }
        else {
            $(this).addClass('snsquicksearch-open');
            $('#search_query_top').focus();
        }

        e.stopPropagation();
    });

    $('#searchbox').on('click', function(e){
        e.stopPropagation();
    });

    $(document).on('click', function()
    {
        if ($('#search_block_top').hasClass('snsquicksearch-open')){
            $('#search_block_top').removeClass('snsquicksearch-open');
            $('.ac_results').hide();
        }
    });

});