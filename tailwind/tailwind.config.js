const path = require("path");

module.exports = {
  content: [
    path.resolve(__dirname, "../**/*.php"),
    path.resolve(__dirname, "../includes/**/*.php"),
    path.resolve(__dirname, "../admin/**/*.php"),
    path.resolve(__dirname, "../assets/js/**/*.js"),
  ],
  theme: {
    extend: {
      colors: {
        "wp-primary": "#2271b1",
      },
    },
  },
  plugins: [],
};
