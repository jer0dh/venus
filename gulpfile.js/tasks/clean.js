const del = require('del');
const { config } = require( '../config/');

function clean() {
    return del( [ config.destFolder ]);
}

exports.clean = clean;
