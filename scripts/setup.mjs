import { existsSync, readFileSync, renameSync, unlinkSync, writeFileSync, mkdirSync } from 'node:fs'
import { dirname, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'
import readline from 'node:readline'

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..')
const configPath = resolve(projectRoot, 'module.config.json')
const config = JSON.parse(readFileSync(configPath, 'utf8'))
const terminal = readline.createInterface({ input: process.stdin, output: process.stdout })
const answers = terminal[Symbol.asyncIterator]()

async function prompt(label, defaultValue, validate) {
  while (true) {
    process.stdout.write(`${label} [${defaultValue}]: `)
    const response = await answers.next()
    const answer = (response.done ? '' : response.value.trim()) || defaultValue
    if (validate(answer)) return answer
    console.error('Invalid value. Please try again.')
  }
}

const validSlug = (value) => /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(value)
const validPhpPrefix = (value) => /^[a-z][a-z0-9_]*$/.test(value)
const validOwner = (value) => /^[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?$/.test(value)
const validRepository = (value) => /^[A-Za-z0-9_.-]+\/[A-Za-z0-9_.-]+$/.test(value)

let pluginName
let slug
let phpPrefix
let githubOwner
let releaseRepository

try {
  pluginName = await prompt(
    'Plugin name',
    config.name,
    (value) => value.length > 0 && !/[\r\n]|\*\//.test(value),
  )
  slug = await prompt('Plugin slug', config.slug, validSlug)
  phpPrefix = await prompt('PHP prefix', config.phpPrefix, validPhpPrefix)
  githubOwner = await prompt('GitHub owner', config.githubOwner, validOwner)

  const defaultReleaseRepository = `${githubOwner}/${slug}`
  const releaseRepositoryInput = await prompt(
    'Release repository (owner/name or name)',
    defaultReleaseRepository,
    (value) => validRepository(value.includes('/') ? value : `${githubOwner}/${value}`),
  )
  releaseRepository = releaseRepositoryInput.includes('/')
    ? releaseRepositoryInput
    : `${githubOwner}/${releaseRepositoryInput}`
} finally {
  terminal.close()
}

const nextConfig = {
  ...config,
  slug,
  name: pluginName,
  phpPrefix,
  githubOwner,
  releaseRepository,
}

const sourcePluginDir = resolve(projectRoot, 'wordpress-plugin', config.slug)
const targetPluginDir = resolve(projectRoot, 'wordpress-plugin', slug)
if (sourcePluginDir !== targetPluginDir && existsSync(targetPluginDir)) {
  throw new Error(`Cannot scaffold into existing plugin directory: ${targetPluginDir}`)
}
if (sourcePluginDir !== targetPluginDir && existsSync(sourcePluginDir)) {
  renameSync(sourcePluginDir, targetPluginDir)
}

const updateUri = `https://github.com/${releaseRepository}`
const manifestUrl = `https://github.com/${releaseRepository}/releases/latest/download/update.json`
const replacements = {
  PLUGIN_NAME: pluginName.replace(/\\/g, '\\\\').replace(/'/g, "\\'"),
  UPDATE_URI: updateUri,
  SLUG: slug,
  PREFIX: phpPrefix,
  PREFIX_UPPER: phpPrefix.toUpperCase(),
  MANIFEST_URL: manifestUrl,
  REQUIRES_WORDPRESS: nextConfig.requiresWordPress,
  REQUIRES_PHP: nextConfig.requiresPhp,
  TESTED_WORDPRESS: nextConfig.testedWordPress,
  GITHUB_OWNER: githubOwner,
  PHP_PREFIX: phpPrefix,
}
const replaceTokens = (template) =>
  template.replace(/\{\{([A-Z_]+)\}\}/g, (_match, key) => {
    if (!(key in replacements)) throw new Error(`Unknown template token: ${key}`)
    return replacements[key]
  })

const pluginDir = targetPluginDir
mkdirSync(pluginDir, { recursive: true })
const previousEntry = resolve(pluginDir, `${config.slug}.php`)
if (config.slug !== slug && existsSync(previousEntry)) {
  unlinkSync(previousEntry)
}
const phpTemplate = readFileSync(resolve(projectRoot, 'templates/plugin.php.tpl'), 'utf8')
const readmeTemplate = readFileSync(resolve(projectRoot, 'templates/readme.txt.tpl'), 'utf8')
writeFileSync(resolve(pluginDir, `${slug}.php`), replaceTokens(phpTemplate))
writeFileSync(resolve(pluginDir, 'readme.txt'), replaceTokens(readmeTemplate))
writeFileSync(configPath, `${JSON.stringify(nextConfig, null, 2)}\n`)

const escapeHtml = (value) =>
  value.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;').replaceAll("'", '&#39;')
const indexPath = resolve(projectRoot, 'index.html')
const indexHtml = readFileSync(indexPath, 'utf8')
  .replace(/<title>.*?<\/title>/, `<title>${escapeHtml(pluginName)}</title>`)
  .replace(/data-[a-z0-9-]+-root/g, `data-${slug}-root`)
writeFileSync(indexPath, indexHtml)

const packagePath = resolve(projectRoot, 'package.json')
const packageJson = JSON.parse(readFileSync(packagePath, 'utf8'))
packageJson.name = slug
writeFileSync(packagePath, `${JSON.stringify(packageJson, null, 2)}\n`)

const lockPath = resolve(projectRoot, 'package-lock.json')
if (existsSync(lockPath)) {
  const lock = JSON.parse(readFileSync(lockPath, 'utf8'))
  lock.name = slug
  if (lock.packages?.['']) lock.packages[''].name = slug
  writeFileSync(lockPath, `${JSON.stringify(lock, null, 2)}\n`)
}

console.log(`Configured ${pluginName} (${slug}) in module.config.json.`)
console.log(`Generated ${resolve(pluginDir, `${slug}.php`)}.`)
