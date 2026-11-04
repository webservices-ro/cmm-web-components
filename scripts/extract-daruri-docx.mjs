import { execFileSync } from 'node:child_process'
import { mkdirSync, writeFileSync } from 'node:fs'
import { basename, resolve } from 'node:path'

const source = process.argv[2]

if (!source) {
  throw new Error('Usage: node scripts/extract-daruri-docx.mjs <document.docx>')
}

const unzipOptions = { maxBuffer: 32 * 1024 * 1024 }
const xml = execFileSync('unzip', ['-p', source, 'word/document.xml'], { ...unzipOptions, encoding: 'utf8' })
const relationships = execFileSync('unzip', ['-p', source, 'word/_rels/document.xml.rels'], { ...unzipOptions, encoding: 'utf8' })
const relationTargets = Object.fromEntries(
  [...relationships.matchAll(/<Relationship\b[^>]*\bId="([^"]+)"[^>]*\bTarget="([^"]+)"[^>]*\/>/g)]
    .map((match) => [match[1], match[2]]),
)

const decodeXml = (value) => value
  .replaceAll('&amp;', '&')
  .replaceAll('&lt;', '<')
  .replaceAll('&gt;', '>')
  .replaceAll('&quot;', '"')
  .replaceAll('&apos;', "'")

const body = xml.match(/<w:body>([\s\S]*?)<\/w:body>/)?.[1] || ''
const blocks = []

for (const paragraphMatch of body.matchAll(/<w:p(?:\s[^>]*)?>([\s\S]*?)<\/w:p>/g)) {
  const paragraph = paragraphMatch[1]
  const style = paragraph.match(/<w:pStyle\b[^>]*w:val="([^"]+)"/)?.[1] || ''
  const text = [...paragraph.matchAll(/<w:t(?:\s[^>]*)?>([\s\S]*?)<\/w:t>|<w:(?:br|cr)\b[^>]*\/>|<w:tab\b[^>]*\/>/g)]
    .map((match) => match[1] === undefined ? (match[0].startsWith('<w:tab') ? '\t' : '\n') : decodeXml(match[1]))
    .join('')
    .trim()
  const images = [...paragraph.matchAll(/<a:blip\b[^>]*r:embed="([^"]+)"/g)]
    .map((match) => relationTargets[match[1]])
    .filter(Boolean)

  if (text) {
    blocks.push({
      type: style === 'Title' ? 'title' : style === 'Heading1' ? 'heading' : style === 'Heading2' ? 'subheading' : style === 'ListParagraph' ? 'listItem' : 'paragraph',
      text,
    })
  }

  if (images.length) {
    blocks.push({ type: 'gallery', images: images.map((image) => basename(image)) })
  }
}

const assetDirectory = resolve('src/assets/daruri-sacre-2026')
mkdirSync(assetDirectory, { recursive: true })

for (const target of new Set(Object.values(relationTargets).filter((value) => value.startsWith('media/')))) {
  const output = execFileSync('unzip', ['-p', source, `word/${target}`], unzipOptions)
  writeFileSync(resolve(assetDirectory, basename(target)), output)
}

const output = `// Generated from ${basename(source)}. The DOCX is the source of truth.\nexport const DARURI_BLOCKS = ${JSON.stringify(blocks, null, 2)}\n`
writeFileSync(resolve('src/daruriContent.js'), output)

console.log(`Extracted ${blocks.length} content blocks and ${new Set(Object.values(relationTargets).filter((value) => value.startsWith('media/'))).size} images.`)
