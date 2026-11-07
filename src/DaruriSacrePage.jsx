import { DARURI_BLOCKS } from './daruriContent.js'

const imageModules = import.meta.glob('./assets/daruri-sacre-2026/*', {
  eager: true,
  query: '?url',
  import: 'default',
})
const images = Object.fromEntries(Object.entries(imageModules).map(([path, url]) => [path.split('/').pop(), url]))
const imageAlt = (index) => `Atmosferă din cadrul atelierului Daruri Sacre ${index + 1}`
const LEGAL_NOTICE = 'Prezentele condiții se aplică fără a aduce atingere drepturilor legale ale consumatorului.'
const EVENT_META = [
  '12 decembrie 2026 · București · 5 ore',
  'Interval orar 10:00- 15:00',
]

function DaruriEventMeta() {
  return (
    <section className="daruri-event-meta" aria-label="Detaliile evenimentului">
      {EVENT_META.map((line) => <p key={line}>{line}</p>)}
    </section>
  )
}

export default function DaruriSacrePage({ config = {} }) {
  let imageIndex = 0
  const whatsappShareUrl = `https://wa.me/?text=${encodeURIComponent(
    `Vreau să împărtășesc cu tine atelierul Daruri Sacre – Ediția 2026: ${window.location.href}`,
  )}`
  const conditionsStartIndex = DARURI_BLOCKS.findIndex((block) => block.text === 'Condiții de rezervare și anulare')
  const conditionBlocks = DARURI_BLOCKS.slice(conditionsStartIndex + 1)
  const conditionGroups = conditionBlocks.reduce((groups, condition) => {
    if (/^\d+\.\s/.test(condition.text)) {
      groups.push({ title: condition.text, paragraphs: [] })
    } else if (condition.text !== LEGAL_NOTICE && groups.length > 0) {
      groups.at(-1).paragraphs.push(condition.text)
    }
    return groups
  }, [])

  return (
    <main className="daruri-page">
      {DARURI_BLOCKS.map((block, index) => {
        if (conditionsStartIndex !== -1 && index > conditionsStartIndex) return null

        if (block.text === EVENT_META[0]) return <DaruriEventMeta key="event-meta" />
        if (block.text === EVENT_META[1]) return null

        if (index === conditionsStartIndex) {
          return (
            <section className="daruri-conditions" key={index} aria-labelledby="daruri-conditions-title">
              <h2 id="daruri-conditions-title">Condiții de rezervare și anulare</h2>
              <div className="daruri-conditions__content">
                {conditionGroups.map((condition) => (
                  <details className="daruri-condition" key={condition.title}>
                    <summary>{condition.title}</summary>
                    <div className="daruri-condition__content">
                      {condition.paragraphs.map((paragraph) => <p key={paragraph}>{paragraph}</p>)}
                    </div>
                  </details>
                ))}
                <p className="daruri-legal-notice">{LEGAL_NOTICE}</p>
              </div>
            </section>
          )
        }

        if (block.type === 'title') {
          return <h1 className="daruri-title" key={index}>{block.text}</h1>
        }
        if (block.type === 'heading') {
          return <h2 className="daruri-heading" key={index}>{block.text}</h2>
        }
        if (block.type === 'subheading') {
          if (block.text === 'Descoperirea lumii plantelor și a aromelor') {
            return <h3 className="daruri-subheading daruri-subheading--single-line" key={index}>{block.text}</h3>
          }
          if (block.text === 'Reconectare cu propriul corp și cu simțurile') {
            return <h3 className="daruri-subheading daruri-subheading--single-line" key={index}>{block.text}</h3>
          }
          return <h3 className="daruri-subheading" key={index}>{block.text}</h3>
        }
        if (block.type === 'listItem') {
          return <p className="daruri-list-item" key={index}>{block.text}</p>
        }
        if (block.type === 'gallery') {
          if (block.images.length === 1 && block.images[0] === 'image4.png') {
            return (
              <div className="daruri-spacer" aria-hidden="true" key={index}>
                <img src={images['image4.png']} alt="" loading="lazy" />
              </div>
            )
          }

          if (block.images.length === 1 && block.images[0] === 'image17.png') {
            return (
              <figure className="daruri-signature" key={index}>
                <img src={images['image17.png']} alt="Semnătura Claudia Maria Murariu" loading="lazy" />
              </figure>
            )
          }

          return (
            <figure className={`daruri-gallery daruri-gallery--${Math.min(block.images.length, 3)}`} key={index}>
              {block.images.map((image) => {
                const currentIndex = imageIndex++
                return <img key={image} src={images[image]} alt={imageAlt(currentIndex)} loading={currentIndex === 0 ? 'eager' : 'lazy'} />
              })}
            </figure>
          )
        }

        if (block.text === '.') return null
        const isEyebrow = index === 1
        const isCallToAction = block.text === 'REZERVĂ-ȚI LOCUL'
        if (isCallToAction) {
          return (
            <div className="daruri-cta-wrap" key={index}>
              <a className="daruri-cta" href={config.reservationUrl || '#rezervare'}>{block.text}</a>
            </div>
          )
        }

        const isLimited = block.text === 'Locurile sunt limitate'
        const isConditionTitle = /^\d+\.\s/.test(block.text)
        const isLegalNotice = block.text === LEGAL_NOTICE
        const className = isEyebrow
          ? 'daruri-eyebrow'
          : isLimited
            ? 'daruri-limited'
            : isConditionTitle
                ? 'daruri-condition-title'
                : isLegalNotice
                  ? 'daruri-legal-notice'
                  : undefined
        return <p className={className} id={isLimited ? 'rezervare' : undefined} key={index}>{block.text}</p>
      })}
      <div className="daruri-share">
        <a href={whatsappShareUrl} target="_blank" rel="noopener noreferrer">
          Trimite unui prieten pe WhatsApp
        </a>
      </div>
    </main>
  )
}
