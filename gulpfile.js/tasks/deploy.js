const {config} = require('../config/');
const deployRemote = require('../lib/deployRemote');


function deploy( cb ){
    deployRemote( config.destFolder, '/');

    cb();
}

exports.deploy = deploy;
