const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/app.js')
  .enableSassLoader()
  .enableSingleRuntimeChunk()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureManifestPlugin(options => {
    options.fileName = 'manifest.json';
  });

module.exports = Encore.getWebpackConfig();