/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Lora', 'serif'], // Apply Lora as your default font
        serif: ['Playfair Display', 'serif'], // Apply Playfair Display as an alternative font
      },
      colors: {
        palePurple: '#E5D4ED',
        mediumSlateBlue: '#6D72C3',
        rebeccaPurple: '#5941A9',
        davyGray: '#514F59',
        darkPurple: '#1D1128',
      },
    },
  },
  plugins: [],
}
