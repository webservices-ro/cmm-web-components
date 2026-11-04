const INTRO_PARAGRAPHS = [
  'Te invit într-un spațiu în care plantele, aromele și bucuria creației se întâlnesc cu descoperirea și înțelegerea propriului corp.',
  'Din perspectiva mea de terapeut, fiecare atelier este mai mult decât o experiență teoretică și practică. Este un prilej de explorare senzorială, de reconectare cu propriile simțuri și emoții și de redescoperire a legăturii dintre om, natură și propria sa natură interioară.',
  'Prin experiențele pe care le construim împreună, te ghidez să devii mai atent la corpul tău, la semnalele pe care ți le transmite, la felul în care simți și la procesele care se desfășoară în interiorul tău. Să descoperi cum plantele, aromele și experiențele senzoriale pot deveni parte dintr-un mod mai conștient de a-ți îngriji sănătatea, de a susține prevenția și de a cultiva o relație mai atentă cu propriul corp.',
  'Aici nu înveți doar să creezi. Înveți să observi, să înțelegi, să simți și să acorzi atenție propriei tale naturi.',
]

function WorkshopCard({ workshop }) {
  return (
    <article className="cmat-card">
      <a className="cmat-card__media" href={workshop.url} aria-label={`Vezi atelierul ${workshop.name}`}>
        {workshop.image ? (
          <img src={workshop.image} alt={workshop.imageAlt || workshop.name} loading="lazy" />
        ) : (
          <span className="cmat-card__placeholder" aria-hidden="true" />
        )}
      </a>
      <div className="cmat-card__body">
        <h3><a href={workshop.url}>{workshop.name}</a></h3>
        {workshop.description && <p className="cmat-card__description">{workshop.description}</p>}
        <div className="cmat-card__meta">
          {workshop.priceHtml && (
            <div className="cmat-card__price" dangerouslySetInnerHTML={{ __html: workshop.priceHtml }} />
          )}
          {workshop.reviewsEnabled && (
            <a className="cmat-card__reviews" href={`${workshop.url}#reviews`}>
              {workshop.reviewCount > 0
                ? `${workshop.reviewCount} ${workshop.reviewCount === 1 ? 'recenzie' : 'recenzii'}`
                : 'Scrie prima recenzie'}
            </a>
          )}
        </div>
        <div className="cmat-card__actions">
          <a className="cmat-button cmat-button--secondary" href={workshop.url}>Vezi prezentarea</a>
          <a className="cmat-button cmat-button--primary" href={workshop.addToCartUrl || workshop.url} rel="nofollow">
            {workshop.buttonText || 'Adaugă în coș'}
          </a>
        </div>
      </div>
    </article>
  )
}

export default function App({ config = {} }) {
  const workshops = Array.isArray(config.workshops) ? config.workshops : []
  const heroStyle = config.heroImage
    ? { '--cmat-hero-image': `url("${config.heroImage.replaceAll('"', '%22')}")` }
    : undefined

  return (
    <main className="cmat-page">
      <section className={`cmat-hero${config.heroImage ? ' cmat-hero--has-image' : ''}`} style={heroStyle}>
        <div className="cmat-hero__veil" aria-hidden="true" />
        <h1>Ateliere botanice terapeutice</h1>
      </section>

      <section className="cmat-intro" aria-labelledby="cmat-intro-title">
        <p className="cmat-section-label">Concept și abordare</p>
        <h2 id="cmat-intro-title">Mai mult decât ateliere de creație</h2>
        <div className="cmat-intro__text">
          {INTRO_PARAGRAPHS.map((paragraph) => <p key={paragraph}>{paragraph}</p>)}
        </div>
        <p className="cmat-signoff">Onorează natura din tine!</p>
      </section>

      <div className="cmat-divider" aria-hidden="true">
        {config.dividerImage && <img src={config.dividerImage} alt="" loading="lazy" />}
      </div>

      {workshops.length > 0 ? (
        <section className="cmat-workshops" aria-label="Ateliere terapeutice">
          <div className="cmat-grid">
            {workshops.map((workshop) => <WorkshopCard key={workshop.id} workshop={workshop} />)}
          </div>
        </section>
      ) : (
        <p className="cmat-pending">Atelier în pregătire</p>
      )}
    </main>
  )
}
