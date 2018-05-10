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
    var pathConfig = require('./'+arguments.pathfile+'.js');
    var paths = pathConfig.array;
} else {
    var paths = defaultPaths; // keep with the default of just the homepage
}


for (var k = 0; k < paths.length; k++) {
    scenarios.push({
        "label": paths[k],
        "url": arguments.testhost+paths[k],
        "referenceUrl": arguments.refhost+paths[k],
        "selectors": [],
        "selectorExpansion": false,
        "readyEvent": "backstopjs_ready",
        "delay": 1000,
        "requireSameDimensions": false,
    });
}

// Configuration
module.exports =
    {
        "id": "prod_test",
        "viewports": [
            {
              "label": "phone",
              "width": 320,
              "height": 1800
            },
            {
              "label": "tablet",
              "width": 1024,
              "height": 1800
            },
            {
              "label": "desktop",
              "width": 2880,
              "height": 1800
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
        "engine": "phantomjs",
        "report": ["browser"],
        "debug": true
    };