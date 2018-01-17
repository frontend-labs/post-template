
var pug = require('pug');
var vfs = require('vinyl-fs');
var map = require('map-stream');
var Buffer = require('buffer').Buffer;

vfs.src(['./pug/**/**/*.pug', '!./pug/**/**/_*.pug'])
    .pipe(map( (data, callback) => {
        pre_html = data.contents.toString('utf8');
        html = pug.render(pre_html, {filename: './pug/', pretty: true});
        data.contents = new Buffer(html);
        data.extname = '.html';
        console.log(data.relative);
        callback(null, data);
    }))
    .pipe(vfs.dest('./output/'));
