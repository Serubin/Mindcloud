/******************************************************************************
 * APICaller.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for api server interaction
 *****************************************************************************/

function APICaller(controller, action) {

	this.API_URL = mindcloud_full_url + "/api/";
	this.cont = controller;
	this.act = action;

	this.send = function(params, callback) {

		params['controller'] = this.cont;
		params['action'] = this.act;

		var success = function(result) {
			//alert(JSON.stringify(result));
			if (result.success == true) {
				callback(result['data']);
			} else {
				log.warning("APICaller", "Failure: " + result['trace']);
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

	this.change = function(controller, action) {
		this.cont = controller;
		this.act = action;
	}
}

$.xhrPool = [];
$.xhrPool.abortAll = function() {
	log.debug("APICaller", "Aborting all calls");
	$(this).each(function(idx, jqXHR) {
		jqXHR.abort();
	});
	$.xhrPool = [];
};

$.ajaxSetup({
	beforeSend: function(jqXHR) {
		$.xhrPool.push(jqXHR);
	},
	complete: function(jqXHR) {
		var index = $.xhrPool.indexOf(jqXHR);
		if (index > -1) {
			$.xhrPool.splice(index, 1);
		}
	}
});