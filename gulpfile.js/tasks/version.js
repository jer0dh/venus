const { src, dest } = require('gulp');
const {config} = require('../config/');
const bump = require('gulp-bump');
const {getPackageJson} = require('../lib/getPackageJson');
const through2 = require('through2');
const {modTime} = require('../lib/modTime');

// Updates the version in package.json.  Using gulp-bump default of {type: 'patch'}. This
// ups the value of the third number in the version.

// minor or major version changes can be done manually as they do not happen frequently.  KISS
function patch() {

    const pkg = getPackageJson();  //maybe reread package.json instead of using cache

    return src('./package.json')
        .pipe(bump({}))
        .pipe(through2.obj( modTime ))
        .pipe(dest('./'))
}

exports.patch = patch;
