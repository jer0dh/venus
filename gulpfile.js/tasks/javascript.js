const { src, dest, parallel } = require('gulp');
const { config } = require( '../config/' );
const filter = require('gulp-filter');
const sourcemaps = require('gulp-sourcemaps');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const rename = require('gulp-rename');

// https://stackoverflow.com/questions/38263316/is-file-path-valid-in-gulp-src
const glob = require('glob');


//Checks to see if file exists.  If not, show error and none of the files are processed.
function checkSrc(paths) {
    paths = (paths instanceof Array) ? paths : [paths];
    var existingPaths = paths.filter(function(path) {
      if (glob.sync(path).length === 0) {
          console.log('\x1b[41m *** ERROR: ' + path + ' doesn\'t exist ***\x1b[0m');
          return false;
      }
      return true;
    });
    return src((paths.length === existingPaths.length) ? paths : []);
  }


  // Concatenate arrays of scripts
const allScripts = config.projectVendorScripts.concat( config.projectScripts );
const negatedProjectVendorScripts = config.projectVendorScripts.map( (s) => '!' + s);
const negatedProjectScripts = config.projectScripts.map( (s) => '!' + s);
const negatedAllScripts = allScripts.map( (s) => '!' + s);


// Creates the main script using the projectVendorScripts and projectScripts in package.json
//      Runs babel on projectScripts
//      Concats scripts based on order in array into a file defined by projectScript
//      Creates a minified version
function mainScript() {
    // A filter to only use scripts in projectScripts - take out vendor scripts
    const noVendorFilter = filter( negatedProjectVendorScripts.concat( config.projectScripts ), {restore: true});
    return checkSrc( allScripts )
    .pipe(sourcemaps.init())
    .pipe( noVendorFilter ) //no babel-ing of vendor files
    .pipe(babel())
    .pipe( noVendorFilter.restore ) //put the vendor files back
    .pipe( concat( config.projectScriptName )) //put all files into single file with projectScriptName
   // .pipe( dest( config.destFolder + '/js')) // copy unminized js to destination - gulp minify appears to keep unminified file in stream
    .pipe( minify({
        ext: {
            min: '.min.js'
        }
    }))
    .pipe(sourcemaps.write('./maps'))
    .pipe( dest( config.destFolder + '/js'));
}

// Babels Minifies any other javascript files under /js and moves to destination
//      except for files in the projectScript arrays, *.min.js files, nor files in the js/vendor directory
function minorScripts() {
    return src([config.srcFolder + '/js/**/*.js', '!'+ config.srcFolder + '/js/vendor/**/*.js', '!' + config.srcFolder + '/js/**/*.min.js'].concat(negatedAllScripts))
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(minify({
        ext: {
            min: '.min.js'
        }
    }))
    .pipe(sourcemaps.write('./maps'))
    .pipe( dest( config.destFolder + '/js'))
}

// Minifies and copies any other javascript file in the vendor folder
function minorVendorScripts() {
    return src([config.srcFolder + '/js/vendor/**/*.js'].concat( negatedProjectVendorScripts ))
    .pipe(sourcemaps.init())
    .pipe(minify({
        ext: {
            min: '.min.js'
        }
    }))
    .pipe(sourcemaps.write('./maps'))
    .pipe( dest( config.destFolder + '/js/vendor'))
}

/**
 * Task to transcript and minify js in the template-parts or other locations in the theme_src
 */

function templatePartsScripts() {
    return src([config.srcFolder + '/template-parts/**/*.js'])
      //  .pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(minify({
            ext: {
                min: '.min.js'
            }
        }))
     //   .pipe(sourcemaps.write('./maps'))
        .pipe( dest( config.destFolder + '/template-parts' ))
}


exports.javascript = parallel( mainScript, minorScripts, minorVendorScripts, templatePartsScripts );
