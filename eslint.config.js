import js from '@eslint/js';
import prettier from 'eslint-config-prettier';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import typescript from 'typescript-eslint';


export default [
    js.configs.recommended,
    ...typescript.configs.recommended,
    {
        ...react.configs.flat.recommended,
        ...react.configs.flat['jsx-runtime'], 
        languageOptions: {
            globals: {
                ...globals.browser,
            },
        },
        rules: {
            'react/react-in-jsx-scope': 'off',
            'react/prop-types': 'off',
            'react/no-unescaped-entities': 'off',
        },
        settings: {
            react: {
                version: 'detect',
            },
        },
    },
    {
        plugins: {
            'react-hooks': reactHooks,
        },
        rules: {
            'react-hooks/rules-of-hooks': 'error',
            'react-hooks/exhaustive-deps': 'warn',
        },
    },
    {
        ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'tailwind.config.js'],
    },
    {
    files: ['database/mongodb/**/*.js'],
    languageOptions: {
        globals: {
            db: 'readonly',
            EJSON: 'readonly',
            print: 'readonly',
            printjson: 'readonly',
            NumberInt: 'readonly',
            NumberLong: 'readonly',
            NumberDecimal: 'readonly',
            ISODate: 'readonly',
            ObjectId: 'readonly',
            require: 'readonly',
        },
    },
    rules: {
        '@typescript-eslint/no-require-imports': 'off',
    },
},
    prettier, 
];
