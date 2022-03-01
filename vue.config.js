const path = require('path')
const devMode = process.env.NODE_ENV !== 'production';


module.exports = {

  //path donde se genera el template base
  indexPath: path.join(__dirname, './templates/index.html.twig'),
  //path donde se buildean los assets
  outputDir: path.resolve(__dirname, './public/build/'),
  //path a los assets publicos
  publicPath: '/build/',
  chainWebpack: config => {
    if (config.plugins.has('copy')) {
      config.plugins.delete('copy')
  }

    config.entry('app').clear()
    config.entry('app').add('./assets/main.js')

    // Add "node_modules" alias
    config.resolve.alias
      .set('node_modules', path.join(__dirname, './node_modules'))
      .set('@', path.join(__dirname, './assets'))

    // Disable "prefetch" plugin since it's not properly working in some browsers
    config.plugins
      .delete('prefetch')

    // Do not remove whitespaces
    config.module.rule('vue')
      .use('vue-loader')
      .loader('vue-loader')
      .tap(options => {
        options.compilerOptions.preserveWhitespace = true
        return options
      })
  }
}
