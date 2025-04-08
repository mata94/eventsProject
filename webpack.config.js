const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addStyleEntry('adminlte', './node_modules/admin-lte/dist/css/adminlte.css')
    .addStyleEntry('bootstrap', './node_modules/bootstrap/dist/css/bootstrap.css')

    .addEntry('app', './assets/app.js')
    .addEntry('adminlte_js', './node_modules/admin-lte/dist/js/adminlte.js')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    .autoProvidejQuery()

    .enableSassLoader()
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();