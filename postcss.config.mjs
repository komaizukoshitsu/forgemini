// postcss.config.mjs
export default { // または module.exports = { ... }; の形でも動く場合があります
  plugins: {
    '@tailwindcss/postcss': {},
    autoprefixer: {}, // 必要であれば残す
  },
};
