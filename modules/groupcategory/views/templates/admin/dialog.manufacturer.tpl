<div id="dialog-manufacturer" class="modal dts-modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title">{l s=' Add manufacturer' mod='groupcategory'}</span>
            </div>
            <div class="modal-body">
                <div class="table-responsive" >
                    <div class="clearfix">
                        <div class="form-inline pull-left">
                            <input type="text" class="form-control keyword" placeholder="{l s='ID or Name' mod='flexiblecustom'}" />
                            <button class="btn btn-default" type="button" onclick="loadManufacturerList('1')"><i class="icon-search"></i> {l s='Search' mod='groupcategory'}</button>
                        </div>                        
                        <div id="dialog-manufacturer-pagination" class="pull-right"></div>
                    </div>
                    <table class="table" id="dialog-manufacturer-list" style="margin-top: 10px">
            			<thead>
            				<tr class="nodrag nodrop">                            
                                <th width="30" class="center">{l s='ID' mod='groupcategory'}</th>
                                <th>{l s='Name' mod='groupcategory'}</th>                                                                
                                <th width="30" class="">{l s='Action' mod='groupcategory'}</th>
                            </tr>				
                        </thead>    
                        <tbody></tbody>
        	       </table>                   
                </div>                
            </div>           
        </div>
    </div>
</div>

{literal}
	<script type="text/javascript" language="JavaScript">
        jQuery(function($){            
            $(document).on('click', '.link-open-dialog-manufacturer', function(){
                loadManufacturerList(1);                
            });
            $(document).on('click','.link-add-manufacturer',function(){        
                var itemId = $(this).attr('data-id');   
                var itemName = $(this).attr('data-name');                
            	$(this).removeClass('link-add-manufacturer').addClass('link-add-manufacturer-off').html('<i class="icon-check-square-o"></i>');
    	        var html = '<li id="manufacturer-'+itemId+'"><input type="hidden" class="manufacturer_id" name="manufacturers[]" value="'+itemId+'" /><span>'+itemName+'</span><a title="delete" href="javascript:void(0)" class="link-trash-manufacturer c-red pull-right" data-id="'+itemId+'"><i class="icon-trash"></i></a></li>';
    	        $("#manufacturer-list").append(html);
        	});
            $(document).on('click','.link-trash-manufacturer',function(){        
                var itemId = $(this).data().id; 
                $("#manufacturer-"+itemId).remove();                
        	});    
        });    
        function loadManufacturerList(page){
            var error = false;
            var keyword = $("#dialog-manufacturer .keyword").val();
            var manufacturers = new Array();
            if($("#modalGroup").hasClass('in')){
            	if($("#modalGroup .manufacturer_id").length >0){
        	    	$("#modalGroup .manufacturer_id").each(function (index){
        	    		productIds[index] = $(this).val();
        	    	});
        	    }    
            }else error = true;            
            if(error == false){
                var data={'action':'loadManufacturerList', 'manufacturers':manufacturers, 'page':page, 'keyword':keyword, 'secure_key':secure_key};
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
                        $("#dialog-manufacturer-pagination").html(response.pagination);
                        $("#dialog-manufacturer-list >tbody").html(response.list);			
                        showModal('dialog-manufacturer', '');							
            		}		
            	});    
            }
            
        }
        
	</script>
{/literal}