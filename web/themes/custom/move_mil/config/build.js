require( './fonts' );
require( './images' );
require( './javascript' );
require( './sass' );

// var fs            = require('fs');
// var path          = require('path');
var child_process = require('child_process');
var gulp          = require('gulp');
var dutil         = require('./doc-util');
var runSequence   = require('run-sequence');
var clean         = require('gulp-clean');
var del           = require('del');

gulp.task('clean-fonts', function () {
  return del('assets/fonts/');
});

gulp.task('clean-images', function () {
  return del('assets/img/');
});

gulp.task('clean-javascript', function () {
  return del('assets/js/');
});

gulp.task('clean-styles', function () {
  return del('assets/css/');
});

gulp.task('remove-assets-folder', function () {
  return del('assets/');
});

gulp.task('clean-assets', gulp.parallel(
    'clean-fonts',
    'clean-images',
    'clean-javascript',
    'clean-styles',
    )
);

gulp.task('build', gulp.series('clean-assets', gulp.parallel('fonts', 'images', 'javascript', 'sass'),
    function(done) {
  dutil.logIntroduction();
  dutil.logMessage(
      'build'
  );
  done();

}));
