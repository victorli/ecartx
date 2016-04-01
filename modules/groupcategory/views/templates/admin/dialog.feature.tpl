<div id="dialog-feature" class="modal dts-modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title">{l s=' Add feature' mod='groupcategory'}</span>
            </div>
            <div class="modal-body">            	
            	<ul id="all-features"></ul>	
            </div>           
        </div>
    </div>
</div>

{literal}
	<script type="text/javascript" language="JavaScript">
		var ext = '';
        jQuery(function($){            	
            $(document).on('click', '.link-open-dialog-feature', function(){
                loadFeatureList();
                                
            });
            $(document).on('click','.link-add-feature',function(){        
                var itemId = $(this).data('id');   
                var itemName = $(this).data('name');                
            	$(this).removeClass('link-add-feature').addClass('link-add-feature-off').html('<i class="icon-check-square-o"></i>');
    	        var html = '<li id="feature-'+itemId+'"><input type="hidden" class="feature_selected" name="features[]" value="'+itemId+'" /><span>'+itemName+'</span><a title="delete" href="javascript:void(0)" class="link-trash-feature c-red pull-right" data-id="'+itemId+'"><i class="icon-trash"></i></a></li>';
    	        $("#list-features").append(html);
        	});
            $(document).on('click','.link-trash-feature',function(){        
                var itemId = $(this).data().id; 
                $("#feature-"+itemId).remove();
                $("#link-add-feature-"+id).removeClass('link-add-feature-off').addClass('link-add-feature').html('<i class="icon-plus"></i>');
        	});    
        });    
        function loadFeatureList(page){
            var error = false;
            var selecteds = new Array();
            if($("#modalGroup").hasClass('in')){
            	if($("#modalGroup .feature_selected").length >0){
        	    	$("#modalGroup .feature_selected").each(function (index){
        	    		selecteds[index] = $(this).val();
        	    	});
        	    }    
            }else error = true;            
            if(error == false){
                var data={'action':'loadFeatureList', 'selecteds':selecteds, 'secure_key':secure_key};
                $.ajax({
            		type:'POST',
            		url: currentUrl,
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){},
            		complete: function(){},
            		success: function(response){
                        $("#all-features").html(response.list);
                        showModal('dialog-feature', ''); 
            		}		
            	});    
            }
            
        }
        
	</script>
{/literal}