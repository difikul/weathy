module.exports = {
  root: true,
  env: {
    browser: true,
    es2021: true,
    node: true,
  },
  extends: [
    'eslint:recommended',
    'plugin:vue/recommended'
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  globals: {
    L: 'readonly',
  },
  rules: {
    'vue/multi-word-component-names': 'off',
    'no-unused-vars': 'off',
  },
};
