/******************************************************************************
 * overrides.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript overrides
 *****************************************************************************/

// Allows size of object to be gotten
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};