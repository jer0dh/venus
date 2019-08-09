const {getPackageJson} = require('../lib/getPackageJson');
const pkg = getPackageJson();


const config = {}


config.production = true;
config.projectName= pkg.name;
config.srcFolder= pkg.projectSrcFolder;
config.destFolder= pkg.projectDestFolder + '/' +  pkg.name;

config.doNotCopyList= [
        '!' + config.srcFolder + '/css/**/*.*',
        '!' + config.srcFolder + '/js/**/*.*',
        '!' + config.srcFolder + '/**/*.scss',
        '!' + config.srcFolder + '/**/*.php',
        '!' + config.srcFolder + '/style.css',
    ];


// Javascript

config.projectScripts = pkg.projectScripts;
config.projectVendorScripts = pkg.projectVendorScripts;
config.projectScriptName = pkg.projectScriptName;

// Check for sftp.json or rsync.json for remote info
let sftp;
try {
    sftp = require('../../sftp.json');
} catch (ex) {
    sftp = { active : false };
}

let rsync;
try {
    rsync = require('../../rsync.json');
} catch (ex) {
    rsync = { active: false };
}

config.sftp= sftp;
config.rsync= rsync;


exports.config = config;
