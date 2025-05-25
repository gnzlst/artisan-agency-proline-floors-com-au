module.exports = {
  content: [
    './resources/**/*.{js,ts,jsx,tsx,php,blade.php}', // Ensure this covers all your template files
    './resources/views/**/*.{php,blade.php}', // Include the sections directory
    './resources/views/**/**/*.{php,blade.php}', // Include the sections directory
    './index.php', // Include the root index.php file
    './functions.php', // Include the root functions.php file
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {},
      fontFamily: {},
      spacing: {},
    },
  },
  plugins: [],
};
