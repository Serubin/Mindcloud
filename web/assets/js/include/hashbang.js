/**
 * parseGet()
 * Parse hash bang parameters from a URL as key value object.
 * #!x&y=3 -> { x:null, y:3 }
 * 
 * @param aURL URL to parse or null if window.location is used
 * @return Object of key -> value mappings.
 */
function parseGet(aURL) {
 
	aURL = aURL || window.location.href;
	
	var vars = {};
	// CHANGE BEFORE MOVING TO PROD
	var sHashes = aURL.slice(aURL.indexOf('web/') + 4).split("/");

	console.log(sHashes);
	
	var hashes = [];
	for (var i = 0; i < sHashes.length; i += 2) {
		if(typeof sHashes[i+1] != "undefined")
			hashes.push(sHashes[i] + "/" + sHashes[i+1]);
		else
			hashes.push(sHashes[i])
	}
	for(var i = 0; i < hashes.length; i++) {
		var hash = hashes[i].split('/');
			console.log(hash)
		if(hash.length > 1) {
			vars[hash[0]] = hash[1];
		} else {
			vars[hash[0]] = null;
		}			
	}
	
	// Returns single string if only one element
	if(Object.size(vars) == 1)
		return Object.keys(vars)[0];

	return vars;
}