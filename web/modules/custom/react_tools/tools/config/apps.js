const _ = require('lodash');
const path = require('path');
const fs = require('fs');
const dir = fs.realpathSync(process.cwd());
const resolveApp = relativePath => path.resolve(dir, relativePath);
const isDirectory = source => fs.lstatSync(source).isDirectory();
const async = require('async');
const { exec } = require('child_process');
const chalk = require('chalk');

function getDirectories(){
  let source = resolveApp('./apps');
  return new Promise((resolve, reject)=>{
    let dirList = fs.readdirSync(source).map(name => path.join(source, name)).filter(isDirectory)
    if(dirList){
      resolve(dirList);
    }else{
      reject(`No dir found under ${source}`);
    }
  });
}

function getEntryPoints(dirList){
  let entryPoints = {};
  // //let polyfills = require.resolve('./polyfills');  // THIS IS IMPERATIVE AS IT FIXES ISSUES WITH IE
  // let polyfills = require("babel-polyfill");  // THIS IS IMPERATIVE AS IT FIXES ISSUES WITH IE
  // console.log(chalk.yellow(polyfills));

  _.each(dirList, (val)=>{
    entryPoints[getAppName(val)] = ["babel-polyfill", `${val}/src/index.js`];
  });

  return entryPoints;
}

function getRequiredFiles(dirList){
  let requiredFiles = [];
  _.each(dirList, (val)=>{
    requiredFiles.push(`${val}/public/index.html`);
    requiredFiles.push(`${val}/src/index.js`);
  });
  return requiredFiles;
}

function getAppName(root){
  let s = root.split('/');
  return s[s.length-1];
}

function getReactAppNames(dirList){
  let appNames = [];
  _.each(dirList, (val)=>{
    appNames.push(getAppName(val));
  });
  return appNames;
}

function appPaths(appName){
  let _appName = appName || process.env.appName;
  let appRoot = _appName ? resolveApp(`./apps/${_appName}`) : resolveApp('./');

  if(!appRoot) return null;
  return {
    dotenv: '.env',
    appBuild: `${appRoot}/build`,
    appPublic: `${appRoot}/public`,
    appHtml: `${appRoot}/public/index.html`,
    appIndexJs: `${appRoot}/src/index.js`,
    packageJson: resolveApp('./package.json'),
    nodeModules: resolveApp('node_modules'),
    appSrc: `${appRoot}/src`,
    sassDir: `${appRoot}/src/sass`,
    cssDir: `${appRoot}/src/localcss`,
    yarnLockFile: `${appRoot}/yarn.lock`,
    testsSetup: `${appRoot}/src/setupTests.js`
  }
}

function sassWatch(appName){
  let appRoots = this.appPaths(appName);
  return new Promise((resolve, reject)=>{
      let sassDir = path.join(appRoots.sassDir, '/main.scss');
      let cssDir = path.join(appRoots.cssDir, '/main.css');
      let script = `node-sass --watch ${sassDir} ${cssDir}`;
      let scriptErr = null;

      exec(script, (err, stdout, stderr) => {
        if(err){
          scriptErr = err;
        }
      });

      setTimeout(()=>{
        if(scriptErr){
          reject(scriptErr);
        }else{
          resolve(`Watching src: ${sassDir} dest: ${cssDir}`);
        }
      }, 100);
  });
}

function buildSass(appRoots){
  return new Promise((resolve, reject)=>{
    let count = 0;
      async.each(appRoots, (app, next) => {
        let sassDir = path.join(app, '/src/sass/main.scss');
        let cssDir = path.join(app, '/src/localcss');
        let script = `node-sass --include-path scss ${sassDir} -o ${cssDir}`;

        exec(script, (err, stdout, stderr) => {
          if (err) {
            console.log(chalk.red(err));
          }else{
            console.log(chalk.magenta(stdout));
            console.log(chalk.magenta(stderr));
            count++;
          }
          next();
        });
      }, (err) => {
        if(err) {
          reject({message: 'Failed too compile one or more css files', error: err});
        } else {
          resolve(`Sucessfully compiled ${count} of ${appRoots.length} css files.`);
        }
    });
  });
}

module.exports = {
  resolvePath: resolveApp,
  reactAppNames: getReactAppNames,
  entryPoints: getEntryPoints,
  requiredFiles: getRequiredFiles(),
  appPaths: appPaths,
  getDirectories,
  buildSass,
  sassWatch
};
