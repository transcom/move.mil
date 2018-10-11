var gulp        = require('gulp');
var gutil       = require('gulp-util')
var dutil       = require('./doc-util');
var sass        = require('gulp-sass');
var linter      = require('gulp-stylelint');
var sourcemaps  = require('gulp-sourcemaps');
var glob        = require('gulp-sass-glob');
var rename      = require('gulp-rename');
var cssnano     = require('gulp-cssnano');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var runSequence = require('run-sequence');

gulp.task('copy-doc-styles', function (done) {

  dutil.logMessage('copy-doc-styles', 'Copying Sass files from css/');

  var stream = gulp.src('./scss/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(glob())
    .on('error', gutil.log)
    .pipe(sass())
    .pipe(postcss([ autoprefixer({
      browsers: ['cover 99.5%', 'last 2 major versions', 'last 3 ie versions'],
      cascade: false
      }) ]))
    .pipe(gulp.dest('assets/css'))
    .pipe(cssnano())
    .pipe(rename('styles.min.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('assets/css/'));

  return stream;

});

gulp.task('copy-uswds-styles', function (done) {

  dutil.logMessage('copy-uswds-styles', 'Copying Sass files from uswds');

  var stream = gulp.src('./node_modules/uswds/src/stylesheets/**/*')
    .pipe(gulp.dest('assets/css/vendor/uswds'));

  return stream;

});

gulp.task('lint-scss', function (done) {

  // if (!cFlags.test) {
  //   dutil.logMessage('lint-scss', 'Skipping linting of Sass files.');
  //   return done();
  // }

  return gulp.src([
    './scss/**/*.scss'
    ])
    .pipe(linter({
      syntax: 'scss',
      reporters: [{formatter: 'verbose', console: true}],
      // fix: true,
      debug: true
    }))
    // .pipe(gulp.dest('scss'))

});

gulp.task('sass', gulp.series('copy-doc-styles'));
