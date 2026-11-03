import { readFileSync, writeFileSync } from 'node:fs'
import { resolve } from 'node:path'

const outputPath = process.argv[2]

if (!outputPath) {
  throw new Error('Usage: node scripts/create-update-manifest.mjs <output-path>')
}

const packageJson = JSON.parse(
  readFileSync(resolve(import.meta.dirname, '..', 'package.json'), 'utf8'),
)

const releaseRepository = 'alexbran8/claudia-maria-web-components-releases'
const releaseBaseUrl = `https://github.com/${releaseRepository}/releases`
const manifest = {
  name: 'Claudia Maria React Components',
  version: packageJson.version,
  download_url: `${releaseBaseUrl}/latest/download/claudia-maria-wp-react.zip`,
  details_url: `${releaseBaseUrl}/latest`,
  requires: '6.0',
  requires_php: '7.4',
  tested: '6.8',
  sections: {
    description: '<p>Responsive React components and pages for the Claudia Maria website, packaged for WordPress and integrated with Elementor design tokens.</p>',
    installation: '<ol><li>Install and activate the plugin.</li><li>Add <code>[cm_homepage]</code> or <code>[cm_pricing]</code> to an Elementor Shortcode widget or WordPress page.</li><li>Optionally configure their URL, popup, and styling attributes.</li></ol>',
    changelog: `<h4>${packageJson.version}</h4><p>Latest Claudia Maria React Components release.</p>`,
  },
}

writeFileSync(outputPath, `${JSON.stringify(manifest, null, 2)}\n`)
