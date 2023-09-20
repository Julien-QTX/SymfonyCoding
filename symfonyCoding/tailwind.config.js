/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: [],
  darkMode: false, 
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwindcss'),
    require('autoprefixer'),
  ],
}