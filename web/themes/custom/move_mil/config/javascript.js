var gulp        = require('gulp');
var gutil       = require('gulp-util');
var dutil       = require('./doc-util');
var concat      = require('gulp-concat');
var buffer      = require('vinyl-buffer');
var source      = require('vinyl-source-stream');
var uglify      = require('gulp-uglify');
var sourcemaps  = require('gulp-sourcemaps');
var rename      = require('gulp-rename');
var linter      = require('gulp-eslint');
var javascript  = 'javascript';

gulp.task('eslint', function (done) {

  // if (!cFlags.test) {
  //   dutil.logMessage('eslint', 'Skipping linting of JavaScript files.');
  //   return done();
  // }

  return gulp.src([
    './js/**/*.js',
    '!./js/vendor/**/*.js'])
    .pipe(linter('.eslintrc.json'))
    .pipe(linter.format());

});

gulp.task('copy-uswds-javascript', function (done) {

  dutil.logMessage(javascript, 'Copying JS from uswds');

  var stream = gulp.src('./node_modules/uswds/dist/js/uswds.min.js')
    .pipe(gulp.dest('assets/js'));

  return stream;

});

gulp.task(javascript, [ 'copy-uswds-javascript'], function (done) {

  dutil.logMessage(javascript, 'Compiling JavaScript');

  return gulp.src('js/**/*.js')
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
      .pipe(concat('scripts.js'))
      .pipe(uglify())
      .on('error', gutil.log)
      .pipe(rename('scripts.min.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('assets/js'));

});
