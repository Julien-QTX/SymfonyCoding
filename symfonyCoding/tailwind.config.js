module.exports = {
  purge: [
    './src/**/*.html',
    './src/**/*.js',
    // Ajoutez d'autres chemins de fichiers ici si nécessaire
  ],
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
};