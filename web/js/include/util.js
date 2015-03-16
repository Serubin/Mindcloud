/******************************************************************************
 * utils.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for async page handling
 *****************************************************************************/

// Global for debug
// 0 - none (default for master)
// 1 - warnings/errors only
// 2 - info (default for develop)
// 3 - debug 
//
var LEVEL = 3;

var log = function(){
	return{

 		debug:function(package,msg){
 			if(LEVEL >= 3)
 				log.raw("DEBUG",package, msg);
 		},
 		info:function(package,msg){
			if(LEVEL >= 2)
 				log.raw("INFO",package, msg);
 		},
 		warning:function(package,msg){
			if(LEVEL >= 1)
 				log.raw("WARNING",package, msg);
 		},
 		raw:function(level,package,msg){
 			console.log(level + ", " + package + ": " + msg);
 		}
 	}
 }();