$(function(){
	$(document).foundation();

	var params = parseGet();
	
	if(Object.size(params) == 1)
		pageRequest(Object.keys(params)[0]);
})