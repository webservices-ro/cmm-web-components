import { DARURI_BLOCKS } from './daruriContent.js'

const imageModules = import.meta.glob('./assets/daruri-sacre-2026/*', {
  eager: true,
  query: '?url',
  import: 'default',
})
const images = Object.fromEntries(Object.entries(imageModules).map(([path, url]) => [path.split('/').pop(), url]))
const imageAlt = (index) => `Atmosferă din cadrul atelierului Daruri Sacre ${index + 1}`

export default function DaruriSacrePage({ config = {} }) {
  let imageIndex = 0

  return (
    <main className="daruri-page">
      {DARURI_BLOCKS.map((block, index) => {
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
        const isConditionsHeading = block.text === 'Condiții de rezervare și anulare'
        const isConditionTitle = /^\d+\.\s/.test(block.text)
        const isLegalNotice = block.text === 'Prezentele condiții se aplică fără a aduce atingere drepturilor legale ale consumatorului.'
        const className = isEyebrow
          ? 'daruri-eyebrow'
          : isLimited
            ? 'daruri-limited'
            : isConditionsHeading
              ? 'daruri-conditions-heading'
              : isConditionTitle
                ? 'daruri-condition-title'
                : isLegalNotice
                  ? 'daruri-legal-notice'
                : undefined
        return <p className={className} id={isLimited ? 'rezervare' : undefined} key={index}>{block.text}</p>
      })}
    </main>
  )
}
