import React from 'react'

const PARAGRAPH_PATTERN = /<p>(.*?)<\/p>/gs
const STRONG_PATTERN = /(<strong>.*?<\/strong>)/gs

function renderInlineContent(content, paragraphIndex) {
  return content.split(STRONG_PATTERN).filter(Boolean).map((part, partIndex) => {
    const match = part.match(/^<strong>(.*?)<\/strong>$/s)

    return match
      ? <strong key={`${paragraphIndex}-${partIndex}`}>{match[1]}</strong>
      : part
  })
}

export default function RichText({ children, className }) {
  const content = typeof children === 'string' ? children : ''
  const paragraphs = [...content.matchAll(PARAGRAPH_PATTERN)].map((match) => match[1])
  const normalizedParagraphs = paragraphs.length ? paragraphs : [content]

  return <div className={className}>
    {normalizedParagraphs.map((paragraph, index) => (
      <p key={index}>{renderInlineContent(paragraph, index)}</p>
    ))}
  </div>
}
