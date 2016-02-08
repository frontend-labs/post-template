
var jade = require('jade');
var vfs = require('vinyl-fs');
var map = require('map-stream');
var Buffer = require('buffer').Buffer;

vfs.src(['./jade/**/**/*.jade', '!./jade/**/**/_*.jade'])
    .pipe(map( (data, callback) => {
        pre_html = data.contents.toString('utf8');
        html = jade.render(pre_html, {filename: './jade/', pretty: true});
        // console.log(html);
        data.contents = new Buffer(html);
        data.extname = '.html';
        console.log(data.relative);
        callback(null, data);
    }))
    .pipe(vfs.dest('./output/'));

