function problem(url){
	// Checks if id or shorthand was submitted
	if(isNaN(parseInt(url[1]))){
		var req = new APICaller('problem', 'getId');
		var params = {shorthand: url[1]};
		req.send(params, function (result) {
			// redirect if no id
			handleNotFound(result);

			// Loads problem
			loadData(result);
		});
	} else {
		loadProblem(url[1]);
	}
}
/**
 *
 *
 */
function loadData(id){
	var req = new APICaller('problem', 'load');
	var params = {"id": id};
	console.log(params);
	req.send(params, function (result) {
		// redirect if no id
		handleNotFound(result);

		// sets up page
		processProblem(result);
	});
}

function processProblem(data){
	console.log(data);
	$("#banner #title").html(data.statement);
	$("#description").html(data.description);

	$("#contributers ul").append("<li><span>" + data.creator.association + "</span> " + data.creator.user.first_name + " " +  data.creator.user.last_name + "</li>")
}
/**
 * handleNotFound()
 * Redirects to dashboard if no id
 */
function handleNotFound(result){
	if(result == false){
		ph.pageRequest("/dashboard");
	}
}