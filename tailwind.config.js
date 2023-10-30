/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/views/content/*.{html,js,php}', 
    './app/views/inc/*.{html,js,php}',
    './index.php'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

