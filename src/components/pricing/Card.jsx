import React from 'react'

export default function Card({ title, price, duration, text, subtitle, note, featured = false, children }) {
  return <article className={`cm-card${featured ? ' cm-card--featured' : ''}`}>
    <h3>{title}{subtitle && <><br /><span>{subtitle}</span></>}</h3>
    {price && <div className="cm-price">{price} {duration && <small>· {duration}</small>}</div>}
    {text && <p>{text}</p>}
    {note && <p className="cm-note">{note}</p>}
    {children}
  </article>
}
