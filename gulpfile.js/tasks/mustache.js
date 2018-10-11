const gulp = require('gulp');
const config = require('../config/');

//const handlebars = require('gulp-handlebars');

//const rename = require('gulp-rename');
const deployRemote = require('../lib/deployRemote');



/**
 *
 */
gulp.task('mustache', function() {
    return gulp.src([config.srcFolder + '/**/*.mustache'])
        //.pipe(handlebars())
        //.pipe(rename({extname: '.compiled.hbs'}))
        .pipe(gulp.dest( config.destination + '/'));
});

gulp.task('mustache-deploy', function() {

    return deployRemote( config.destination + '/**/*.mustache', '/' );

});
