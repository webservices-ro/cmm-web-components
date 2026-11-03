import React from 'react'

export default function Section({ kicker, title, intro, children }) {
  return <section className="cm-section">
    <div className="cm-container">
      <div className="cm-kicker">{kicker}</div>
      {title && <h2>{title}</h2>}
      {intro && <p className="cm-intro">{intro}</p>}
      {children}
    </div>
  </section>
}
