const { src, dest, parallel } = require('gulp');

const  { config } = require('../config/');

const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const template = require('gulp-template');
const {getPackageJson} = require('../lib/getPackageJson');

// Create CSS files unminified with source maps
function stylesMaxSass() {
    return src( [config.srcFolder + '/**/*.scss'])
        .pipe(sourcemaps.init())
        .pipe(template( {pkg:getPackageJson() }))
        .pipe(sass().on('error', sass.logError))
        .pipe( autoprefixer({}))
        .pipe(sourcemaps.write( './maps'))
        .pipe(dest( config.destFolder))
}

// Create CSS files minified with source maps
function stylesMinSass() {
    return src( [config.srcFolder + '/**/*.scss'])
        .pipe(sourcemaps.init())
        .pipe(template( {pkg:getPackageJson() }))
        .pipe(sass({outputStyle: 'compressed', sourceMap: true}).on('error', sass.logError))
        .pipe( autoprefixer())
        .pipe(rename(function(path){
            path.extname = '.min.css'
        }))
        .pipe(sourcemaps.write( './maps'))
        .pipe(dest( config.destFolder))
}

exports.styles = parallel(stylesMaxSass, stylesMinSass);
