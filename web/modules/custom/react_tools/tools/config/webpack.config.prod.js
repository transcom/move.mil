'use strict';

const autoprefixer = require('autoprefixer');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const InterpolateHtmlPlugin = require('react-dev-utils/InterpolateHtmlPlugin');
const eslintFormatter = require('react-dev-utils/eslintFormatter');
const paths = require('./paths');
const getClientEnvironment = require('./env');
const apps = require('./apps');
const publicPath = paths.servedPath;
const shouldUseRelativeAssetPaths = publicPath === './';
const shouldUseSourceMap = false;
const publicUrl = publicPath.slice(0, -1);
const env = getClientEnvironment(publicUrl);

function getConfig() {
  return new Promise((resolve, reject) => {
    apps.getDirectories()
      .then((appDirList) => {
        const entryPoints = apps.entryPoints(appDirList);

        console.log('google key: ' + process.env.GOOGLE_MAPS_API_KEY);

        if (env.stringified['process.env'].NODE_ENV !== '"production"') {
          throw new Error('Production builds must have NODE_ENV=production.');
        }
        const cssFilename = 'static/css/[name].min.css';
        const extractTextPluginOptions = shouldUseRelativeAssetPaths
        ? // Making sure that the publicPath goes back to to build folder.
          { publicPath: Array(cssFilename.split('/').length).join('../') }
        : {};

        resolve(
          {
          appsDirList: appDirList,
          config: {
            bail: true,
            devtool: shouldUseSourceMap ? 'source-map' : false,
            entry: entryPoints,
            output: {
              path: paths.appBuild,
              filename: 'static/js/[name].min.js',
              chunkFilename: 'static/js/[name].chunk.js',
              publicPath: publicPath
            },
            resolve: {
              modules: ['node_modules'],
              extensions: ['.web.js', '.mjs', '.js', '.json', '.web.jsx', '.jsx'],
              alias: {
                'react-native': 'react-native-web',
              }
            },
            module: {
              strictExportPresence: true,
              rules: [
                {
                  test: /\.(js|jsx|mjs)$/,
                  enforce: 'pre',
                  use: [
                    {
                      options: {
                        formatter: eslintFormatter,
                        eslintPath: require.resolve('eslint'),
                        
                      },
                      loader: require.resolve('eslint-loader'),
                    },
                  ]
                },
                {
                  // "oneOf" will traverse all following loaders until one will
                  // match the requirements. When no loader matches it will fall
                  // back to the "file" loader at the end of the loader list.
                  oneOf: [
                    {
                      test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/],
                      loader: require.resolve('url-loader'),
                      options: {
                        limit: 10000,
                        name: 'static/media/[name].[ext]',
                      },
                    },
                    {
                      test: /\.(js|jsx|mjs)$/,
                      exclude: /node_modules/, // MUST exclude node-modules or babel error
                      loader: require.resolve('babel-loader'),
                      options: {
                        compact: true,
                      },
                    },
                    {
                      test: /\.css$/,
                      loader: ExtractTextPlugin.extract(
                        Object.assign(
                          {
                            fallback: {
                              loader: require.resolve('style-loader'),
                              options: {
                                hmr: false,
                              },
                            },
                            use: [
                              {
                                loader: require.resolve('css-loader'),
                                options: {
                                  importLoaders: 1,
                                  minimize: true,
                                  sourceMap: shouldUseSourceMap,
                                },
                              },
                              {
                                loader: require.resolve('postcss-loader'),
                                options: {
                                  ident: 'postcss',
                                  plugins: () => [
                                    require('postcss-flexbugs-fixes'),
                                    autoprefixer({
                                      browsers: [
                                        '>1%',
                                        'last 4 versions',
                                        'Firefox ESR',
                                        'not ie < 9', // React doesn't support IE8 anyway
                                      ],
                                      flexbox: 'no-2009',
                                    }),
                                  ],
                                },
                              },
                            ],
                          },
                          extractTextPluginOptions
                        )
                      ),
                    },
                    {
                      loader: require.resolve('file-loader'),
                      exclude: [/\.(js|jsx|mjs)$/, /\.html$/, /\.json$/],
                      options: {
                        name: 'static/media/[name].[ext]',
                      },
                    },
                  ],
                },
              ],
            },
            plugins: [
              new InterpolateHtmlPlugin(env.raw),
              new webpack.DefinePlugin(env.stringified),
              // Minify the code.
              new webpack.optimize.UglifyJsPlugin({
                compress: {
                  warnings: false,
                  comparisons: false,
                },
                mangle: {
                  safari10: true,
                },
                output: {
                  comments: false,
                  ascii_only: true,
                },
                sourceMap: shouldUseSourceMap,
              }),
              // Note: this won't work without ExtractTextPlugin.extract(..) in `loaders`.
              new ExtractTextPlugin({
                filename: cssFilename,
              }),
              new ManifestPlugin({
                fileName: 'asset-manifest.json',
              }),
              new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
            ],
            // Some libraries import Node modules but don't use them in the browser.
            // Tell Webpack to provide empty mocks for them so importing them works.
            node: {
              dgram: 'empty',
              fs: 'empty',
              net: 'empty',
              tls: 'empty',
              child_process: 'empty',
            },
          }
        });
    }).catch((err)=>{
      reject(err);
    });
  });
}

module.exports = {
  getConfig: getConfig
}