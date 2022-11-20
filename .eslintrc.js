module.exports = {
  extends: [
    'plugin:vue/vue3-strongly-recommended',
    'alloy',
    'alloy/vue',
  ],
  parserOptions: {
    parser: '@babel/eslint-parser',
  },
  rules: {
    eqeqeq: 0,
    indent: ['error', 2],
    'no-return-assign': 0,
    'no-undef': 0,
    'vue/component-tags-order': 0,
    'vue/eqeqeq': 0,
    'vue/html-indent': ['error', 2, {
      'attribute': 1,
      'baseIndent': 1,
      'closeBracket': 0,
      'alignAttributesVertically': false,
      'ignores': [],
    }],
    'vue/max-attributes-per-line': ['error', {
      'singleline': {
        'max': 1,
      },
      'multiline': {
        'max': 1,
      },
    }],
    'vue/multi-word-component-names': 0,
    'vue/no-arrow-functions-in-watch': 0,
    'vue/no-deprecated-events-api': 0,
    'vue/no-deprecated-v-on-native-modifier': 0,
    'vue/no-duplicate-attributes': ['error', {
      'allowCoexistClass': true,
      'allowCoexistStyle': true,
    }],
    'vue/no-multiple-template-root': 0,
    'vue/no-reserved-component-names': 0,
    'vue/no-v-for-template-key': 0,
    'vue/no-v-for-template-key-on-child': 0,
    'vue/no-v-model-argument': 0,
    'vue/require-explicit-emits': 0,
    'vue/require-prop-types': 0,
  },
};
