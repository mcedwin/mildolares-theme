module.exports = {
  content: [
    "./**/*.php",
    "./**/*.js"
  ],
  theme: {
    container: {
      center: true,
      padding: "1rem",
    },
    extend: {
      colors: {
        primary: "#111111",
        accent: "#2563eb"
      },
      maxWidth: {
        'site': '1160px',
      }
    }
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
  safelist: [
    'bg-black',
    'text-white',
    'px-6',
    'py-3',
    'rounded-md',
    'mt-4',
    'border-gray-300',
    'border'
  ]
}