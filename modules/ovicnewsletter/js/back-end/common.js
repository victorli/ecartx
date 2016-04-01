String.prototype.escapeSpecialChars = function() {
    return this.replace(/\\n/g, "\\n")
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
};
function handleEnterNumber(event){
	var keyCode = event.keyCode ? event.keyCode : event.charCode;	
	if((keyCode < 48 || keyCode > 58) && keyCode != 8 && keyCode != 13 && keyCode != 9 && keyCode != 35 && keyCode != 36 && keyCode != 99 && keyCode != 118 && keyCode != 46 && keyCode != 37 && keyCode != 39 && keyCode != 45){
		return false;
	}		
}
function handleEnterNumberInt(event){
	var keyCode = event.keyCode ? event.keyCode : event.charCode;
	if((keyCode < 48 || keyCode > 58) && keyCode != 8 && keyCode != 13 && keyCode != 9 && keyCode != 35 && keyCode != 36 && keyCode != 99 && keyCode != 118 && keyCode != 37 && keyCode != 39 && keyCode != 45){
		return false;
	}		
}
$(document).ready(function(){
    tinySetup();
});
function changeLanguage(langId){	
    $(".lang-"+currentLang).hide();
    $(".lang-"+langId).show();
    currentLang =  langId;	
	$(".lang").each(function() {
		$(this).val(langId);        
    });
    
    
}
