import React from 'react'

export const defaultFooterNavigation = [
  { label: 'Despre mine', url: '/despre-mine/' },
  { label: 'Cum lucrăm', url: '/cum-lucram/' },
  { label: 'Terapii', url: '/servicii/' },
  { label: 'Prețuri', url: '/preturi/' },
  { label: 'Resurse', url: '/resurse/' },
  { label: 'Cursuri', url: '/cursuri/' },
]

export const defaultFooterLegalLinks = [
  { label: 'Notificare legală', url: 'https://centrulsakura.ro/notificare-legala/' },
  { label: 'Privacy', url: 'https://centrulsakura.ro/privacy/' },
  { label: 'Drept autor', url: 'https://centrulsakura.ro/drept-autor/' },
  { label: 'Înștiințare cursuri', url: 'https://centrulsakura.ro/instiintare-cursuri/' },
  { label: 'Termeni și condiții', url: 'https://centrulsakura.ro/termeni-si-conditii/' },
  { label: 'Cookies', url: 'https://centrulsakura.ro/cookies/' },
]

const socialLinks = [
  { key: 'facebook', label: 'Facebook', mark: 'f' },
  { key: 'instagram', label: 'Instagram', mark: 'ig' },
  { key: 'youtube', label: 'YouTube', mark: '▶' },
]

export const defaultPaymentMethods = ['METODĂ DE PLATĂ', 'CARD — DACĂ ESTE ACTIV', 'PROCESATOR — DACĂ ESTE ACTIV']

function PaymentScript({ code }) {
  return <iframe className="cm-footer-payment__script" sandbox="allow-popups allow-popups-to-escape-sandbox allow-scripts" srcDoc={`<!doctype html><html><head><style>html,body{align-items:center;background:transparent;display:flex;height:100%;justify-content:center;margin:0;overflow:hidden;width:100%}img{display:block;max-height:100%;max-width:100%}</style></head><body>${code}</body></html>`} title="Siglă procesator de plăți" />
}

export default function Footer({ brand = 'CLAUDIA MARIA', bridge = 'Centrul Sakura by Claudia Maria', contactText = 'Programările se stabilesc telefonic, după ce discutăm despre nevoile tale.', copyright = '© Claudia Maria — Toate drepturile rezervate', email = 'claudiamariamed@gmail.com', exploreHeading = 'Explorează', contactHeading = 'Contact', homeUrl = '/', legalLinks = defaultFooterLegalLinks, logoImageUrl = '', navigation = defaultFooterNavigation, paymentHeading = 'Informații despre plată', paymentImages = [], paymentMethods = defaultPaymentMethods, paymentNote = 'Notă pentru implementare: înlocuiți aceste marcaje cu siglele oficiale numai dacă metodele respective sunt efectiv disponibile. Verificați separat obligațiile de informare legală; elementele obligatorii nu trebuie ascunse sau eliminate.', paymentScript = '', paymentText = 'Detaliile privind plata și eventualele condiții de programare sunt comunicate înainte de confirmarea serviciului. Dacă pe site se activează plata online, aici pot fi afișate metodele de plată acceptate, folosind siglele oficiale corespunzătoare.', phone = '0769 231 018', showPaymentInfo = false, social = {}, tagline = 'Un spațiu în care corpul tău este ascultat, înțeles și sprijinit.' }) {
  const siteBase = homeUrl === '/' ? '' : homeUrl.replace(/\/$/, '')
  const resolveUrl = (url) => /^(?:[a-z]+:|#)/i.test(url || '') ? url : `${siteBase}${url || '#'}`
  const phoneHref = phone.replace(/[^+\d]/g, '')
  const visiblePaymentImages = paymentImages.filter((image) => image?.url)
  const renderPaymentImage = (image, index) => image.link
    ? <a className="cm-footer-payment__image" href={image.link} key={`${image.url}-${index}`} rel="noopener noreferrer" target="_blank"><img alt={image.alt || ''} src={image.url} /></a>
    : <span className="cm-footer-payment__image" key={`${image.url}-${index}`}><img alt={image.alt || ''} src={image.url} /></span>

  return <div className="cm-footer-scope">
    {showPaymentInfo && <section className="cm-footer-payment" aria-label="Informații despre plată">
      <div className={`cm-footer-payment__wrap${!paymentHeading && !paymentText ? ' cm-footer-payment__wrap--logos-only' : ''}`}>
        {(paymentHeading || paymentText) && <div>
          {paymentHeading && <h2>{paymentHeading}</h2>}
          {paymentText && <p>{paymentText}</p>}
        </div>}
        <div className="cm-footer-payment__methods" aria-label="Metode de plată">
          {visiblePaymentImages.length > 0 ? renderPaymentImage(visiblePaymentImages[0], 0) : !paymentScript && paymentMethods.map((method, index) => <span className="cm-footer-payment__chip" key={`${method}-${index}`}>{method}</span>)}
          {paymentScript && <PaymentScript code={paymentScript} />}
          {visiblePaymentImages.slice(1).map((image, index) => renderPaymentImage(image, index + 1))}
        </div>
      </div>
      {paymentNote && <p className="cm-footer-payment__note">{paymentNote}</p>}
    </section>}
    <footer className="cm-footer">
    <div className="cm-footer__wrap">
      <div className="cm-footer__top">
        <div className="cm-footer__brand-block">
          <a className="cm-footer__brand" href={resolveUrl('/')}>{logoImageUrl ? <img alt={brand} src={logoImageUrl} /> : brand}</a>
          {bridge && <p className="cm-footer__bridge">{bridge}</p>}
          {tagline && <p className="cm-footer__tagline">{tagline}</p>}
        </div>
        <nav aria-label="Navigare footer">
          <h2>{exploreHeading}</h2>
          <ul>{navigation.map((item, index) => <li key={`${item.label}-${index}`}><a href={resolveUrl(item.url)}>{item.label}</a></li>)}</ul>
        </nav>
        <div className="cm-footer__contact">
          <h2>{contactHeading}</h2>
          {phone && <p><a href={`tel:${phoneHref}`}>{phone}</a></p>}
          {email && <p><a href={`mailto:${email}`}>{email}</a></p>}
          {contactText && <p>{contactText}</p>}
          <div className="cm-footer__social" aria-label="Rețele sociale">
            {socialLinks.map((item) => social[item.key] ? <a href={social[item.key]} aria-label={item.label} key={item.key} rel="noopener noreferrer" target="_blank">{item.mark}</a> : null)}
          </div>
        </div>
      </div>
      <div className="cm-footer__bottom">
        <nav className="cm-footer__legal" aria-label="Informații legale">{legalLinks.map((item, index) => <a href={resolveUrl(item.url)} key={`${item.label}-${index}`}>{item.label}</a>)}</nav>
        <div className="cm-footer__copyright">{copyright}</div>
      </div>
    </div>
  </footer></div>
}
