
npm install from root dir

To compile development CSS from Sass (and watch for changed):
  $ cd src/sass
  $ sass --watch main.scss:../localcss/main.css --style nested

  * note: if dir localcss is not created it will be.

To run development environment from localhost:3000:
  $cd <approot>
  $ npm start

  * note: this needs to be running in order to debug.

To run internally (withing drupal): (compiles build folder)
  $cd <approot>
  $ npm run build