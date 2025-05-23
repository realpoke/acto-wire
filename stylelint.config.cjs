module.exports = {
    extends: [
        'stylelint-config-standard',
        'stylelint-config-tailwindcss'
    ],
    // you can customize or add rules here if needed, e.g.
    // rules: {
    //   'declaration-empty-line-before': null
    // },
    ignoreFiles: [
        'node_modules/**',
        'vendor/**',
        'public/**'
    ]
};

