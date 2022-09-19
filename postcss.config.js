let tailwindcss = require("tailwindcss")

module.exports = {
  // plugins: {
  //   tailwindcss: {},
  //   autoprefixer: {},
  // },
  plugins: [
    tailwindcss('./tailwindcss.config.js'),
    require('postcss-import'),
    require('autoprefixer')
  ],
}
