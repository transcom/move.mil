# Custom move_mil Subtheme
Extends and customizes imported parent theme built by 18F, incorporating US Web Design Standards (USWDS) components into a base Drupal theme.
[See details on the USWDS contrib theme](https://www.drupal.org/project/uswds)


## Installation

## Setup

2. In your terminal, navigate to the theme folder: `cd <project root>/web/themes/custom/move_mil`
3. run `npm install`


## Usage

### Local/Dev
1. In your command line, run `npm run clean` to remove any legacy files in your `assets` folder
2. check the `assets` folder. It should be empty other than a `.gitkeep` file. Manually delete any other remaining files.
3. In your command line, run `npm run lint` to try out linting css and js code. With this current code base, the css linter should error out due to exactly one breaking error, though there will be a few other warning-level violations. There is not yet any custom javascript to lint, so that process will complete smoothly.
4. Run `npm run watch` to both dynamically build the assets folder and watch the custom theme's  source folders for further changes. NOTE: the linters will NOT run through watch, just build scripts. You still have to run the linters manually.
5. When all changes are made and ready for test/push, run `npm run lint`,
6. If the js and/or scss linters flag needed changes, make those changes, rerun the linters until the issues are resolved, then run `npm run build` one final time to integrate the changes (this is only necessary for local testing, not for code deployment).

### Production
1. repeat steps 1-2 above
2. Run `npm run build` to generate production-ready assets.
