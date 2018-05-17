# Weight Estimator Tool
## move.mil weight estimator tool - native react

# Run Locally

 $cd {rootDir} -- this file location 
 $ npm install

 ## Compile development CSS from Sass (and watch for changed):
  $ cd src/sass
  $ sass --watch main.scss:../localcss/main.css --style nested

  * note: if dir localcss is not created it will be.

 ## Run development environment from localhost:3000:
  $ npm start

  * note: this needs to be running in order to debug.

# Build to Drupal (production)

 ## To run internally (withing drupal): (compiles build folder)
  $ npm run build