/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Outfit"', 'sans-serif'],
      },
      fontSize: {
        'xs': '0.625rem', 
        'sm': '0.75rem', 
        'base': '0.875rem',
        'lg': '1rem', 
        'xl': '1.125rem', 
        '2xl': '1.25rem',
        '3xl': '1.5rem', 
        '4xl': '1.625rem',
      },
      colors: {
        brand: { 
          50: '#eff6ff', 
          100: '#dbeafe', 
          500: '#3b82f6', 
          600: '#2563eb', 
          900: '#1e3a8a' 
        },
        fresh: { 
          400: '#22d3ee', 
          500: '#06b6d4' 
        }
      },
      boxShadow: {
        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
        'glow': '0 0 20px rgba(59, 130, 246, 0.5)',
      },
      animation: {
        'float': 'float 6s ease-in-out infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        }
      }
    },
  },
  plugins: [],
}