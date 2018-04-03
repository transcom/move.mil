
var gulp  = require('gulp');
var dutil = require('./doc-util');
var fonts  = 'fonts';

gulp.task(fonts, function (done) {

  dutil.logMessage(fonts, 'Copying fonts from uswds');

  var stream = gulp.src('./node_modules/uswds/src/fonts/**/*')
    .pipe(gulp.dest('assets/fonts'));

  return stream;

});
