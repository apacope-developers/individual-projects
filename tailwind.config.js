/** @type {import('tailwindcss').Config} */
export default {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        display: ['Space Grotesk', 'sans-serif'],
        body: ['DM Sans', 'sans-serif'],
      },
      colors: {
        med: {
          red: '#EF4444',
          redLight: '#FCA5A5',
          teal: '#2DD4BF',
          tealDark: '#0D9488',
        }
      }
    }
  },
  plugins: [],
}