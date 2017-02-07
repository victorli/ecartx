
$(document).ready( function(){
	$(".sns-patterns").each( function(){
		var wrap = this;
		if( $("input",wrap).val() ){	
		//	$("#" + $("input",wrap).val()).addClass("active"); 
		}
		$('a').each(function(){
			if($("input",wrap).val() == $(this).attr("data-value")) {
				$(this).addClass("active");
			}
		});
		$("a",this).click( function(){
		 	  $("input",wrap).val( $(this).attr("data-value") );
			  $("a",wrap).removeClass( "active" );
			  $(this).addClass("active");
		} );
	} );
} );