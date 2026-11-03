import React, { useMemo } from 'react'

const ALLOWED_ELEMENTS = new Set(['a', 'bdi', 'del', 'img', 'ins', 'li', 'p', 'span', 'strong', 'ul'])
const ALLOWED_ATTRIBUTES = new Set(['alt', 'class', 'decoding', 'height', 'href', 'loading', 'rel', 'src', 'target', 'title', 'width'])
const ATTRIBUTE_NAMES = { class: 'className' }

function isSafeUrl(value, allowImageData = false) {
  const normalized = value.trim().toLowerCase()
  return !normalized.startsWith('javascript:') && !normalized.startsWith('vbscript:') && (allowImageData || !normalized.startsWith('data:'))
}

function getAttributes(element) {
  return [...element.attributes].reduce((attributes, attribute) => {
    const name = attribute.name.toLowerCase()
    if (!ALLOWED_ATTRIBUTES.has(name) && !name.startsWith('aria-') && !name.startsWith('data-')) return attributes
    if ((name === 'href' || name === 'src') && !isSafeUrl(attribute.value, name === 'src' && attribute.value.toLowerCase().startsWith('data:image/'))) return attributes

    attributes[ATTRIBUTE_NAMES[name] || name] = attribute.value
    return attributes
  }, {})
}

function toReactNode(node, key) {
  if (node.nodeType === Node.TEXT_NODE) return node.textContent
  if (node.nodeType !== Node.ELEMENT_NODE) return null

  const tagName = node.tagName.toLowerCase()
  if (!ALLOWED_ELEMENTS.has(tagName)) {
    return <React.Fragment key={key}>{[...node.childNodes].map((child, index) => toReactNode(child, `${key}-${index}`))}</React.Fragment>
  }

  return React.createElement(
    tagName,
    { ...getAttributes(node), key },
    tagName === 'img' ? undefined : [...node.childNodes].map((child, index) => toReactNode(child, `${key}-${index}`)),
  )
}

export default function SafeHtml({ children }) {
  return useMemo(() => {
    if (typeof children !== 'string' || !children) return null

    const document = new DOMParser().parseFromString(children, 'text/html')
    return [...document.body.childNodes].map((node, index) => toReactNode(node, String(index)))
  }, [children])
}
