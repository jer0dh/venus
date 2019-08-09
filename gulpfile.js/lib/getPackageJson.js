const fs = require('fs');

const getPackageJson =  ()  => {
    return JSON.parse(fs.readFileSync('./package.json', 'utf8'));
};

exports.getPackageJson = getPackageJson;
