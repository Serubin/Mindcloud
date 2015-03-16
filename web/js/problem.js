function problem(url){
	
	if(isNaN(parseInt(url[1]))){
		var req = new APICaller('problem', 'getId');
		var params = {shorthand: url[1]};
		req.send(params, function (result) {
			if(result == false){
				ph.pageRequest("/dashboard");
			}
			console.log(result);
			loadProblem(result);
		});
	} else {
		loadProblem(url[1]);
	}
}

function loadProblem(id){
	var req = new APICaller('problem', 'load');
	var params = {"id": id};
	console.log(params);
	req.send(params, function (result) {
		console.log(result);
	});
}