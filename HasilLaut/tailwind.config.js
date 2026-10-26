/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'midnight-teal': '#013137',
        'ocean-teal': '#215E68',
        'dusty-turq': '#297376',
        'soft-seafoam': '#5C9396',
        'pale-ice': '#C1D9DE',
      }
    },
  },
  plugins: [],
}