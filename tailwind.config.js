/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.jsx",
  ],
  darkMode: false,
  theme: {
    extend: {
      colors: {
        brand: {
          bg: "#FAFBFC",
          dark: "#16425B",
          primary: "#2F6690",
          secondary: "#3A7CA5",
          text: "#293241",
        },
      },
      fontFamily: {
        sans: ["Geist", "ui-sans-serif", "system-ui", "-apple-system", "Segoe UI", "Roboto", "sans-serif"],
      },
      // borderless cards rely entirely on these very soft shadows for separation
      boxShadow: {
        xs: "0 1px 2px 0 rgba(22, 66, 91, 0.04)",
        card: "0 1px 3px 0 rgba(22, 66, 91, 0.05), 0 1px 2px -1px rgba(22, 66, 91, 0.04)",
        pop: "0 4px 12px -2px rgba(22, 66, 91, 0.08)",
      },
      borderRadius: {
        xl: "0.875rem",
        "2xl": "1.25rem",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};