function problem(url){
	console.log(url);
	console.log("loading problem");
	var req = new APICaller('problem', 'load');
	var params = {id: url[2]};
	req.send(params, function (result) {
		console.log(result);
	});
}