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

/* color
 *
 * Array of mindcloud colors
 */
 var color = {
 	primary: '#4754a4', // blue
 	primary_light_2: '#707ab8',
 	primary_light: '#5b67ae',
 	primary_dark: '#3f4b92',
 	primary_dark_2: '#374180',

 	secondary: '#ff7600', // orange
 	secondary_light_2: '#ff9132',
 	secondary_light: '#ff8319',
 	secondary_dark: '#e56a00',
 	secondary_dark_2: '#cc5e00'
 }

/* 
 * select a ranom image
 */
 function randImgSelect () {

 	var no = Math.floor(Math.random()*7 + 1);

 	return 'url("/assets/images/splash/' + no + '.jpg")';

 }

/**
 * returns undefined and redirects to page
 */
function returnTo(location){
	ph.pageRequest(location);
	return;
}