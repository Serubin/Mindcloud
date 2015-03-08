/******************************************************************************
 * APICaller.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for api server interaction
 *****************************************************************************/

function APICaller (controller, action) {

	this.API_URL = "http://mindcloud.loc/api/";
	this.cont = controller;
	this.act = action;

	this.send = function (params, callback) {

		params['controller'] = this.cont;
		params['action'] = this.act;

		var success = function(result) {
				//alert(JSON.stringify(result));
				if (result.success == true) {
					callback(result['data']);
				}
				else {
					console.log("Failure: " + result['error']);
					callback(false);
				}
		};

		$.ajax({
			type: "POST",
			url: this.API_URL,
			data: params,
			success: success,
			dataType: "json",
			xhrFields: {
			    withCredentials: true
			}
		});

	};

	this.change = function (controller, action) {
		this.cont = controller;
		this.act = action;
	}
}