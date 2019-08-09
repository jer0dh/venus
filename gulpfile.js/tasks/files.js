const { src, dest } = require('gulp');
const { config } = require('../config/');
const newer = require('gulp-newer');

function filesCopy() {

    return src([config.srcFolder + '/**/*.*'].concat(config.doNotCopyList))
        .pipe(newer( config.destFolder ))
        .pipe(dest( config.destFolder ));
}

exports.filesCopy = filesCopy