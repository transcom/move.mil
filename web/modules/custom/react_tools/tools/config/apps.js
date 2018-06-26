const _ = require('lodash');
const appsDirectories = './apps';
const appRoots = {
  entitlements: './apps/react-entitlements-page',
  locator: './apps/react-locator-map',
  ppm: './apps/react-ppm-tool',
  weight: './apps/react-weight-estimator' 
}

function getEntryPoints(){
  let entryPoints = {};
  _.each(appRoots, (val, key)=>{
    entryPoints[key] = `${val}/src/index.js`;
  });

  return entryPoints;
}

function createHtmlWebPackConfigs(){
  let htmlPaths = [];
  _.each(appRoots, (val, key)=>{
    htmlPaths.push({
      inject: false,
      chunks: key,
      filename: `${val}/public/index.html`,
      minify: {
        removeComments: true,
        collapseWhitespace: true,
        removeRedundantAttributes: true,
        useShortDoctype: true,
        removeEmptyAttributes: true,
        removeStyleLinkTypeAttributes: true,
        keepClosingSlash: true,
        minifyJS: true,
        minifyCSS: true,
        minifyURLs: true,
      },
    });
  });
}

module.exports = {
  entryPoints: getEntryPoints(),
  htmlPaths: createHtmlWebPackConfigs()
};
