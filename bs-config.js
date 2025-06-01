module.exports = {
  proxy: "teraokanatsumicom.local", // ← Local by Flywheel のローカルドメイン
  files: [
    "**/*.php",
    "./assets/css/*.css",
    "./**/*.html",
    "./**/*.js"
  ],
  open: false,
  notify: false
};
