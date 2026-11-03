import { readFileSync, writeFileSync } from 'node:fs'
import { resolve } from 'node:path'

const outputPath = process.argv[2]
if (!outputPath) {
  throw new Error('Usage: node scripts/create-update-manifest.mjs <output-path>')
}

const projectRoot = resolve(import.meta.dirname, '..')
const config = JSON.parse(readFileSync(resolve(projectRoot, 'module.config.json'), 'utf8'))
const packageJson = JSON.parse(readFileSync(resolve(projectRoot, 'package.json'), 'utf8'))
const releaseBaseUrl = `https://github.com/${config.releaseRepository}/releases`
const escapeHtml = (value) =>
  value.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;').replaceAll("'", '&#39;')
const pluginName = escapeHtml(config.name)
const manifest = {
  name: config.name,
  version: packageJson.version,
  download_url: `${releaseBaseUrl}/latest/download/${config.slug}.zip`,
  details_url: `${releaseBaseUrl}/latest`,
  requires: config.requiresWordPress,
  requires_php: config.requiresPhp,
  tested: config.testedWordPress,
  sections: {
    description: `<p>${pluginName} provides a minimal React module for WordPress.</p>`,
    installation: `<ol><li>Install and activate the plugin.</li><li>Add the <code>[${config.phpPrefix}]</code> shortcode to a page or post.</li></ol>`,
    changelog: `<h4>${packageJson.version}</h4><p>Latest ${pluginName} release.</p>`,
  },
}

writeFileSync(outputPath, `${JSON.stringify(manifest, null, 2)}\n`)
