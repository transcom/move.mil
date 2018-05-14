/*
  How to use

  backstop reference --configPath=backstop-settings.js
       backstop test --configPath=backstop-settings.js

  backstop reference --configPath=backstop-settings.js --refhost=http://example.com
       backstop test --configPath=backstop-settings.js --testhost=http://example.com

  backstop reference --configPath=backstop-settings.js --paths=/,/contact
       backstop test --configPath=backstop-settings.js --paths=/,/contact

  backstop reference --configPath=backstop-settings.js --pathfile=paths
       backstop test --configPath=backstop-settings.js --pathfile=paths

*/

/*
  Set up some variables
 */
var arguments = require('minimist')(process.argv.slice(2)); // grabs the process arguments
var defaultPaths = ['/']; // By default is just checks the homepage
var scenarios = []; // The array that'll have the pages to test

/*
  Work out the environments that are being compared
 */
// The host to reference
if (!arguments.refhost) {
    arguments.refhost  = "http://move-mil-stage.us-east-1.elasticbeanstalk.com/"; // Default refhost host
}

// The host to test
if (!arguments.testhost) {
    arguments.testhost  = "http://move.mil.localhost:8000/"; // Default test host
}

/*
  Work out which paths to use, either a supplied array, an array from a file, or the defaults
 */
if (arguments.paths) {
    pathString = arguments.paths;
    var paths = pathString.split(',');
} else if (arguments.pathfile) {
    var pathConfig = require('./'+arguments.pathfile);
    var paths = pathConfig.array;
} else {
    var paths = defaultPaths; // keep with the default of just the homepage
}

for (var i = 0; i < paths.length; i++) {
    scenarios.push({
        "label": paths[i],
        "url": arguments.testhost+paths[i],
        "referenceUrl": arguments.refhost+paths[i],
        "selectors": [
          "document"
        ],
        "selectorExpansion": true,
        "delay": 1000,
        "misMatchThreshold" : 0.1,
        "removeSelectors": []
    });
}

// our breakpoints for ref
// $xsmall-screen: 321px !default;
// $small-screen:  481px !default;
// $medium-screen: 600px !default;
// $medium-large-screen: 951px !default;
// $large-screen:  1201px !default;

// Configuration
module.exports =
    {
        "id": "vis_test",
        "viewports": [
            {
              "label": "x-small",
              "width": 321,
              "height": 6000
            },
            {
              "label": "small-screen",
              "width": 481,
              "height": 6000
            },
            {
              "label": "medium-screen",
              "width": 600,
              "height": 6000
            },
            {
              "label": "medium-large-screen",
              "width": 951,
              "height": 6000
            },
            {
              "label": "large-screen",
              "width": 1201,
              "height": 6000
            }
        ],
        "scenarios": scenarios,
        "paths": {
            "bitmaps_reference": "backstop_data/bitmaps_reference",
            "bitmaps_test":      "backstop_data/bitmaps_test",
            "casper_scripts":    "backstop_data/casper_scripts",
            "html_report":       "backstop_data/html_report",
            "ci_report":         "backstop_data/ci_report"
        },
        "casperFlags": [],
        "engine": [
          "chromy"
        ],
        "report": ["browser"],
        "asyncCaptureLimit": 5,
        "asyncCompareLimit": 50,
        "resembleOutputOptions": {
            "errorColor": {
              "red": 255,
              "green": 0,
              "blue": 255
            },
            "errorType": "movement",
            "transparency": 0.3,
            "ignoreAntialiasing": true
          }
    };
