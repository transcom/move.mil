const _ = require('lodash');
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

function getRequiredFiles(){
  let requiredFiles = [];
  _.each(appRoots, (val, key)=>{
    requiredFiles.push(`${val}/public/index.html`);
    requiredFiles.push(`${val}/src/index.js`);
  });
  return requiredFiles;
}

module.exports = {
  entryPoints: getEntryPoints(),
  requiredFiles: getRequiredFiles()
};
