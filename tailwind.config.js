/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [ 
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors:{
        'main-green': '#109646',
        'secondary-green': '#8AC774',
        'bg-white': '#ffffff',
        'white': '#ffffff',
      }
    },
  },
  plugins: [],
}

