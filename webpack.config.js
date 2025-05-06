const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')

  // Entry for JS (loads Alpine, etc.)
  .addEntry('app', './assets/app.js')

  // Entry for CSS (loaded via <link> in base.html.twig)
  //.addStyleEntry('styles', './assets/styles/app.css')

  // Enable PostCSS (for Tailwind v4+)
  .enablePostCssLoader()

  // Optional: Babel config
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = '3.38';
  })

  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableBuildNotifications()
  .cleanupOutputBeforeBuild();
;

module.exports = Encore.getWebpackConfig();