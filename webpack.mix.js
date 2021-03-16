let mix = require('laravel-mix')

mix
  .setPublicPath('dist')
  .js('resources/tool/js/tool.js', 'js')
  .sass('resources/tool/sass/tool.scss', 'css')
