/**
 * Copy woff2 from @fontsource into public/fonts (used by public/assets/css/fonts-*.css).
 * Run: npm run copy-fonts
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const copies = [
  [
    'node_modules/@fontsource/inter/files',
    'public/fonts/inter',
    [
      'inter-latin-400-normal.woff2',
      'inter-latin-600-normal.woff2',
      'inter-latin-700-normal.woff2',
      'inter-latin-800-normal.woff2',
    ],
  ],
  [
    'node_modules/@fontsource/figtree/files',
    'public/fonts/figtree',
    [
      'figtree-latin-400-normal.woff2',
      'figtree-latin-500-normal.woff2',
      'figtree-latin-600-normal.woff2',
    ],
  ],
];

for (const [fromDir, toDir, files] of copies) {
  const absTo = path.join(root, toDir);
  fs.mkdirSync(absTo, { recursive: true });
  for (const f of files) {
    const from = path.join(root, fromDir, f);
    const to = path.join(absTo, f);
    if (!fs.existsSync(from)) {
      console.error('Missing font file (run npm install):', from);
      process.exit(1);
    }
    fs.copyFileSync(from, to);
  }
}
console.log('Font assets copied to public/fonts/');
