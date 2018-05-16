# Run Locally

  1. $cd <rootDir> -- this file location

  2. $ npm install

  3. Compile development CSS from Sass (and watch for changed):
    $ cd src/sass
    $ sass --watch main.scss:../localcss/main.css --style nested

    * note: if dir localcss is not created it will be.

  4. Run development environment from localhost:3000:
    $ npm start

    * note: this needs to be running in order to debug.

# Build to Drupal (production)

  1. To run internally (withing drupal): (compiles build folder)
     $ npm run build