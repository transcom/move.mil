var gulp  = require('gulp');
var dutil = require('./doc-util');
var images  = 'images';

gulp.task('copy-doc-images', function (done) {

  dutil.logMessage(images, 'Copying images from theme images folder');

  var stream = gulp.src('./images/**/*')
    .pipe(gulp.dest('assets/img'));

  return stream;

});

gulp.task('copy-uswds-images', function (done) {

  dutil.logMessage(images, 'Copying images from uswds');

  var stream = gulp.src('./node_modules/uswds/src/img/**/*')
    .pipe(gulp.dest('assets/img'));

  return stream;

});

gulp.task(images, gulp.series('copy-doc-images', 'copy-uswds-images', function(done) {

  dutil.logMessage(images, 'Copying images Sass');
  done();

}));
