
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './templates/**/*.php'
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
