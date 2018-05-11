# Custom move_mil Subtheme
Extends and customizes imported parent theme built by 18F, incorporating US Web Design Standards (USWDS) components into a base Drupal theme.
[See details on the USWDS contrib theme](https://www.drupal.org/project/uswds)


## Installation

## Setup

3. run `npm install`


## Usage

### Local/Dev
1. In your command line, run `npm run clean` to remove any legacy files in your `assets` folder
2. Check the `assets` folder. It should be empty other than a `.gitkeep` file. Manually delete any other remaining files.
3. Run `npm run watch` to both dynamically build the assets folder and watch the custom theme's source folders for further changes.

### Visual Testing
We are using [backstop.js](https://github.com/garris/BackstopJS) to run visual regression testing.

You can set configuration and update testing scripts at
- `backstop-path.js` to set up an array of paths for our test to run
- `backstop-settings.js` to configure our scenarios and process the test

From the command line in the theme root
1. Run `npm run vis-ref` to capture current reference images from the source url and store them in backstop_data.
2. Run `npm run vis-test`, this will both capture current images from your local:: and compare them with our reference images that is launched as a new tab in the browser from which you can inspect the diffs.

### Production
1. Repeat steps 1-2 above
2. Run `npm run build` to generate production-ready assets.
