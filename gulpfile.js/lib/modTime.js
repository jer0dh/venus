  exports.modTime = function( file, enc, cb ) {
      let date = new Date();
      file.stat.atime = date;
      file.stat.mtime = date;
      cb( null, file );
  }
