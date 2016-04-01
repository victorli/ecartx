{if $instantsearch}
	<script type="text/javascript">
	// <![CDATA[
		function tryToCloseInstantSearch() {
			if ($('#old_center_column').length > 0)
			{
				$('#center_column').remove();
				$('#old_center_column').attr('id', 'center_column');
				$('#center_column').show();
				return false;
			}
		}
		
		instantSearchQueries = new Array();
		function stopInstantSearchQueries(){
			for(i=0;i<instantSearchQueries.length;i++) {
				instantSearchQueries[i].abort();
			}
			instantSearchQueries = new Array();
		}
		
		$("#search_query_{$categorysearch_type}").keyup(function(){
			if($(this).val().length > 0){
				stopInstantSearchQueries();
				instantSearchQuery = $.ajax({
					url: '{if $search_ssl == 1}{$link->getModuleLink('categorysearch', 'catesearch', array(), true)|addslashes}{else}{$link->getModuleLink('categorysearch', 'catesearch')|addslashes}{/if}',
					data: {
						instantSearch: 1,
						id_lang: {$cookie->id_lang},
						q: $(this).val()
					},
					dataType: 'html',
					type: 'POST',
					success: function(data){
						if($("#search_query_{$categorysearch_type}").val().length > 0)
						{
							tryToCloseInstantSearch();
							$('#center_column').attr('id', 'old_center_column');
							$('#old_center_column').after('<div id="center_column" class="' + $('#old_center_column').attr('class') + '">'+data+'</div>');
							$('#old_center_column').hide();
							// Button override
							ajaxCart.overrideButtonsInThePage();
							$("#instant_search_results a.close").click(function() {
								$("#search_query_{$categorysearch_type}").val('');
								return tryToCloseInstantSearch();
							});
							return false;
						}
						else
							tryToCloseInstantSearch();
					}
				});
				instantSearchQueries.push(instantSearchQuery);
			}
			else
				tryToCloseInstantSearch();
		});
	// ]]>
	</script>
{/if}
{if $ajaxsearch}
	<script type="text/javascript">
    var moduleDir = "{$module_dir}";
	var searchUrl = baseDir + 'modules/categorysearch/finds.php?rand=' + new Date().getTime();
    var maxResults = 15;
    //var search_category = $('#search_category option:selected').val()
	// <![CDATA[
		$('document').ready( function() {
            var select = $( "#search_category" ),
            options = select.find( "option" ),
            selectType = options.filter( ":selected" ).attr( "value" );
            
            $("#search_query_{$categorysearch_type}").autocomplete(
                searchUrl, {
        			minChars: 3,
        			max: maxResults,
        			width: 500,
        			selectFirst: false,
        			scroll: false,
                    cacheLength: 0,
        			dataType: "json",
        			formatItem: function(data, i, max, value, term) {
        				return value;
        			},
        			parse: function(data) {
							var mytab = new Array();
							for (var i = 0; i < data.length; i++)
								mytab[mytab.length] = { data: data[i], value: '<img src="' + data[i].product_image + '" alt="' + data[i].pname + '" height="30" /> &nbsp;&nbsp;' + data[i].cname + ' > ' + data[i].pname, icon: data[i].product_image};
							return mytab;
						},
        			extraParams: {
        				ajax_Search: 1,
        				id_lang: {$cookie->id_lang},
                        id_category: selectType
        			}
                }
            )
            .result(function(event, data, formatted) {
				$('#search_query_{$categorysearch_type}').val(data.pname);
				document.location.href = data.product_link;
			});
        
            select.change(function () {
                selectType = options.filter( ":selected" ).attr( "value" );
                $( ".ac_results" ).remove();
                $("#search_query_{$categorysearch_type}").autocomplete(
                    searchUrl, {						
            			minChars: 3,
            			max: maxResults,
            			width: 500,
            			selectFirst: false,
            			scroll: false,
                        cacheLength: 0,
            			dataType: "json",
            			formatItem: function(data, i, max, value, term) {
            				return value;
            			},
            			parse: function(data) {
            			     
							var mytab = new Array();
							for (var i = 0; i < data.length; i++)
								mytab[mytab.length] = { data: data[i], value: data[i].cname + ' > ' + data[i].pname };
                                mytab[mytab.length] = { data: data[i], value: '<img src="' + data[i].product_image + '" alt="' + data[i].pname + '" height="30" />' + '<span class="ac_product_name">' + pname + '</span>' };
							return mytab;
						},
            			extraParams: {
            				ajax_Search: 1,
            				id_lang: {$cookie->id_lang},
                            id_category: selectType
            			}
                    }
                );
            });
		});
	// ]]>
	</script>
{/if}

