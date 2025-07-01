const Encore = require('@symfony/webpack-encore');

Encore
    // dossier de sortie des fichiers compilés
    .setOutputPath('public/build/')
    // chemin public d’accès aux assets dans Twig
    .setPublicPath('/build')
    // fichier JS d'entrée (change le chemin selon ton projet)
    .addEntry('app', './assets/app.js')
    // active les sourcemaps en dev
    .enableSourceMaps(!Encore.isProduction())
    // active le versioning des fichiers en prod
    .enableVersioning(Encore.isProduction())
    // nettoie le dossier de build avant compilation
    .cleanupOutputBeforeBuild()
;

module.exports = Encore.getWebpackConfig();
