// CommonJS so it loads in both Bun and Node
const htmlParser = require('@html-eslint/parser');
const alpinejs   = require('eslint-plugin-alpinejs');
const cspell     = require('@cspell/eslint-plugin');

const alpineRecommended = alpinejs.configs.recommended ?? { rules: {} };

module.exports = [

    /* ─ Blade views ───────────────────────────── */
    {
        files: ['resources/**/*.blade.php'],

        languageOptions: { parser: htmlParser },

        plugins: {
            alpinejs,
            '@cspell': cspell,
        },

        rules: {
            ...alpineRecommended.rules,          // alpine best-practices
            '@cspell/spellchecker': ['error', {   // spell-check visible copy
                autoFix      : true,
                checkComments: false,              // ignore <!-- comments -->
            }],
        },
    },

    /* ─ Plain JS / TS modules ─────────────────── */
    {
        files: ['resources/js/**/*.{js,jsx,ts,tsx}'],

        plugins: { '@cspell': cspell },

        rules: {
            '@cspell/spellchecker': ['error', { autoFix: true }],
        },
    },
];

