import React, { useEffect, useState } from 'react'
import HomeButton from './HomeButton'
import RichText from './RichText'
import heroImage from '../../assets/home/hero.jpg'
import aboutImage from '../../assets/home/about.jpg'
import therapyManualImage from '../../assets/home/therapy-manual.jpg'
import therapyReflexologyImage from '../../assets/home/therapy-reflexology.jpg'
import coursesImage from '../../assets/home/courses.jpg'
import homepageContent from '../../../wordpress-plugin/claudia-maria-wp-react/content/homepage.json'

function TestimonialCards({ items, rich = false }) {
  const [selected, setSelected] = useState(null)

  useEffect(() => {
    if (!selected) return undefined
    const previousOverflow = document.body.style.overflow
    const previousPaddingRight = document.body.style.paddingRight
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth
    const closeOnEscape = (event) => event.key === 'Escape' && setSelected(null)

    if (scrollbarWidth > 0) {
      const currentPaddingRight = Number.parseFloat(window.getComputedStyle(document.body).paddingRight) || 0
      document.body.style.paddingRight = `${currentPaddingRight + scrollbarWidth}px`
    }
    document.body.style.overflow = 'hidden'
    window.addEventListener('keydown', closeOnEscape)
    return () => {
      document.body.style.overflow = previousOverflow
      document.body.style.paddingRight = previousPaddingRight
      window.removeEventListener('keydown', closeOnEscape)
    }
  }, [selected])

  return <>
    {items.map((item) => <article className="cm-home-testimonial" key={item.title}>
      <p className="cm-home-testimonial-preview">{item.text}</p>
      <small>— {item.title}</small>
      <button aria-haspopup="dialog" className="cm-home-testimonial-more" onClick={() => setSelected(item)} type="button">VEZI MAI MULT →</button>
    </article>)}
    {selected && <div className="cm-testimonial-modal" onMouseDown={(event) => event.target === event.currentTarget && setSelected(null)} role="presentation">
      <section aria-labelledby="cm-testimonial-modal-title" aria-modal="true" className="cm-testimonial-modal__dialog" role="dialog">
        <button aria-label="Închide testimonialul" autoFocus className="cm-testimonial-modal__close" onClick={() => setSelected(null)} type="button">×</button>
        <div aria-hidden="true" className="cm-testimonial-modal__quote">”</div>
        <h3 id="cm-testimonial-modal-title">Testimonial</h3>
        {rich ? <RichText className="cm-testimonial-modal__content">{selected.full || selected.text}</RichText> : <div className="cm-testimonial-modal__content"><p>{selected.text}</p></div>}
        <p className="cm-testimonial-modal__author">— {selected.title}</p>
      </section>
    </div>}
  </>
}

export function Hero({ bookingUrl, bookingPopupId, imageUrl = heroImage }) {
  return <section className="cm-home-hero">
    <div className="cm-home-container cm-home-hero__grid">
      <div className="cm-home-hero__copy">
        <div className="cm-home-eyebrow">balneofiziokinetoterapeut / REFLEXOTERAPEUT / FORMATOR</div>
        <h1>Nu trebuie să știi<br />de ce terapie ai nevoie.</h1>
        <p className="cm-home-hero__lead">Vii cu ceea ce simți.<br /><strong>Începem de acolo.</strong></p>
        <p className="cm-home-hero__text"><em>Te ascult, privim împreună și descoperim, pas cu pas, de ce are nevoie corpul tău acum.</em></p>
        <div className="cm-home-meta">PREVENȚIE • RECUPERARE • ABORDARE INTEGRATIVĂ</div>
        <HomeButton href={bookingUrl} popupId={bookingPopupId}>PROGRAMEAZĂ O CONSULTAȚIE</HomeButton>
      </div>
      <div className="cm-home-hero__photo"><img alt="Claudia Maria în cabinet, așezată într-un fotoliu galben, cu element anatomic cranio-facial în fundal" src={imageUrl} /></div>
    </div>
  </section>
}

export function CentralIdea() {
  return <section className="cm-home-section cm-home-central"><div className="cm-home-container">
    <div className="cm-home-kicker">CONCEPT ȘI ABORDARE</div>
    <h2>Nu terapia este punctul de plecare. Tu ești.</h2>
    <p className="cm-home-intro">Nu cred că există o singură terapie potrivită pentru toți oamenii. Fiecare om are povestea lui. Fiecare corp se adaptează diferit. Iar ceea ce simțim astăzi poate avea în spate mai multe lucruri, atât emoțional cât și somatic, care merită privite și abordate împreună.</p>
    <p className="cm-home-intro">De aceea, nu aleg o terapie înainte să te cunosc. Te ascult. Te evaluez. Privesc ansamblul. Apoi alegem împreună direcția și abordarea de lucru.</p>
    <p className="cm-home-quote">Pentru mine, terapia este doar un instrument.<br /><strong>Omul este punctul de plecare.</strong></p>
    <div className="cm-home-summary">{['ASCULT', 'EVALUEZ', 'ALEG', 'LUCREZ', 'REEVALUEZ'].map((item) => <span key={item}>{item}</span>)}</div>
    <HomeButton href="#cum" secondary>AFLĂ CUM LUCRĂM →</HomeButton>
  </div></section>
}

export function About({ imageUrl = aboutImage }) {
  return <section className="cm-home-section cm-home-soft" id="despre"><div className="cm-home-container cm-home-about">
    <div className="cm-home-about__content">
      <div className="cm-home-kicker">Despre mine</div><h2>De ce fac ceea ce fac</h2>
      <p className="cm-home-intro">Sunt Claudia Maria Murariu, balneofiziokinetoterapeut și formator. Curiozitatea pentru corpul uman a început pentru mine cu mult înainte să devină profesie. Am vrut mereu să înțeleg de ce ne îmbolnăvim, de ce ne doare (căci eu de mică am trăit cu dureri și afecțiuni pe care mi le-am diagnosticat singură), de ce fiecare om este diferit și, mai ales, ce îl ajută să se adapteze și să se refacă.</p>
      <p className="cm-home-intro">Am studiat științele naturii, apoi balneofiziokinetoterapie și recuperare medicală la universitatea de medicină și farmacie, apoi master în prevenția și recuperarea cardiovasculară și pulmonară, iar de-a lungul anilor am continuat să învăț și să caut.</p>
      <p className="cm-home-intro">Am trecut prin foarte multe cursuri și numeroase formări, mulți ani de practică în terapii manuale, osteopatie și diferite abordări terapeutice complementare. Cu cât am învățat mai mult, cu atât am înțeles că organismul uman este mult mai complex decât pare. Și tocmai de aceea nu caut să pun omul într-o metodă sau într-un protocol, căci fiecare om este unic. Caut să înțeleg omul din fața mea. Lucrez personalizat cu fiecare.</p>
      <HomeButton href="#contact" secondary>AFLĂ POVESTEA MEA →</HomeButton>
    </div>
    <div className="cm-home-about__photo"><img alt="Claudia Maria în cabinet, lucrând cu un model anatomic al coloanei" src={imageUrl} /></div>
  </div></section>
}

export function Audience() {
  const { kicker, title, quote, cards } = homepageContent.audience
  return <section className="cm-home-section" id="pentru-cine"><div className="cm-home-container">
    <div className="cm-home-kicker">{kicker}</div><h2>{title}</h2>
    <div className="cm-home-grid cm-home-grid--three">{cards.map((card) => <article className="cm-home-card" key={card.title}><h3>{card.title}</h3><RichText>{card.text}</RichText></article>)}</div>
    <p className="cm-home-quote">{quote}</p>
    <HomeButton href="#contact" secondary>VEZI PENTRU CINE ESTE →</HomeButton>
  </div></section>
}

export function Therapies({ therapiesUrl, natureUrl, manualImageUrl = therapyManualImage, reflexologyImageUrl = therapyReflexologyImage, raindropImageUrl, ritualImageUrl }) {
  const { kicker, title, intro, cards, natural } = homepageContent.therapies
  return <section className="cm-home-section cm-home-cream" id="terapii"><div className="cm-home-container">
    <div className="cm-home-kicker">{kicker}</div><h2>{title}</h2>
    <p className="cm-home-intro cm-home-services-intro">{intro}</p>
    <div className="cm-home-therapy-visuals">
      <div><img alt="Claudia Maria în timpul unei terapii manuale, cu mâinile asupra capului" src={manualImageUrl} /></div>
      <div><img alt="Tehnică de neuroreflexoterapie plantară" src={reflexologyImageUrl} /></div>
    </div>
    <div className="cm-home-grid cm-home-grid--services">
      {cards.map((card) => <article className={`cm-home-service-card${card.featured === 'yes' ? ' is-featured' : ''}`} key={card.title}><h3>{card.title}</h3><RichText>{card.text}</RichText><a className="cm-home-service-link" href={therapiesUrl}>{card.link_label}</a></article>)}
    </div>
    <div className="cm-home-all-services"><HomeButton href={therapiesUrl} secondary>VEZI TOATE SERVICIILE →</HomeButton></div>
    <div className="cm-home-natural"><h3>{natural.title}</h3>{raindropImageUrl && <div className="cm-home-natural__image"><img alt="Natură pentru sănătate" src={raindropImageUrl} /></div>}<RichText>{natural.body}</RichText><HomeButton href={natureUrl} secondary>DESCOPERĂ NATURĂ PENTRU SĂNĂTATE →</HomeButton><div className="cm-home-raindrop"><h3>{natural.raindrop.title}</h3><RichText>{natural.raindrop.body}</RichText><HomeButton href={natural.raindrop.link} secondary>{natural.raindrop.link_label}</HomeButton>{ritualImageUrl && <div className="cm-home-raindrop__image"><img alt="Claudia Maria în timpul ritualului Raindrop" src={ritualImageUrl} /></div>}</div></div>
  </div></section>
}

export function Process() {
  const { kicker, title, steps } = homepageContent.process
  return <section className="cm-home-section" id="cum"><div className="cm-home-container"><div className="cm-home-kicker">{kicker}</div><h2>{title}</h2><div className="cm-home-steps">{steps.map((step, index) => <article className="cm-home-step" key={step.title}><div className="cm-home-step__number">{String(index + 1).padStart(2, '0')}</div><h3>{step.title}</h3><p>{step.text}</p></article>)}</div></div></section>
}

export function IntegrativeMedicine() {
  return <section className="cm-home-section cm-home-soft"><div className="cm-home-container cm-home-integrative"><div className="cm-home-kicker">Medicină integrativă</div><h2>Medicina integrativă înseamnă să privim omul ca întreg.</h2><p className="cm-home-intro">Cred că sănătatea nu se construiește într-un singur loc și nici printr-o singură metodă. Omul este unic și complex și trebuie văzut și abordat ca atare.</p><p className="cm-home-intro">Uneori avem nevoie de medicina convențională. Alteori, putem adăuga și alte forme de sprijin, atunci când ele sunt potrivite pentru noi.</p><p className="cm-home-intro">Dacă ai un diagnostic sau urmezi un tratament medical, acesta este parte din povestea ta și țin cont de el atunci când lucrăm.</p><p className="cm-home-quote"><strong>Nu înlocuiesc medicina convențională. Sunt aici să aduc un sprijin în plus, acolo unde poate fi de folos.</strong></p></div></section>
}

export function Testimonials() {
  const { items, google_url: googleUrl } = homepageContent.patient_testimonials
  return <section className="cs-testimonials" aria-labelledby="cs-testimonials-title"><div className="cs-testimonials__inner">
    <p className="cs-testimonials__eyebrow">CUM ÎMI AJUT PACIENȚII</p>
    <h2 className="cs-testimonials__title" id="cs-testimonials-title">Experiențele lor</h2>
    <div className="cm-home-grid cm-home-grid--three cs-testimonials__grid"><TestimonialCards items={items} /></div>
    <div className="cs-testimonials__footer"><a className="cs-testimonials__link" href={googleUrl} target="_blank" rel="noopener noreferrer">VEZI TOATE TESTIMONIALELE →</a></div>
  </div></section>
}

export function Resources() {
  const resources = [['Articole', 'Despre corp, sănătate, recuperare și lucrurile pe care le descopăr în practica mea.'], ['Ghiduri', 'Materiale practice pe care le poți descărca și folosi.'], ['Meditații', 'Practici gratuite și materiale pe care le poți aprofunda.']]
  return <section className="cm-home-section cm-home-cream" id="resurse"><div className="cm-home-container"><div className="cm-home-kicker">Resurse</div><h2>Cred că atunci când înțelegi, începi să ai mai multă putere și speranță.</h2><p className="cm-home-intro">De aceea, vreau ca aici să găsești informații care să te ajute să-ți înțelegi mai bine corpul și să privești sănătatea cu mai multă claritate.</p><div className="cm-home-grid cm-home-grid--three">{resources.map(([title, text]) => <article className="cm-home-resource" key={title}><h3>{title}</h3><p>{text}</p></article>)}</div></div></section>
}

export function Courses({ coursesUrl, imageUrl = coursesImage }) {
  return <section className="cm-home-section" id="cursuri"><div className="cm-home-container cm-home-courses"><div><div className="cm-home-kicker">Cursuri &amp; formare</div><h2>Și dacă vrei să înveți, te aștept și acolo.</h2><p className="cm-home-intro">De-a lungul anilor, experiența mea nu a rămas doar în cabinet. Am început să predau, să împărtășesc ceea ce am învățat și să creez propriile cursuri și formări pentru oameni care vor să înțeleagă corpul dintr-o altă perspectivă.</p><p className="cm-home-intro"><strong>Workshop-uri • Cursuri profesionale • Formări</strong></p><HomeButton href={coursesUrl} secondary>DESCOPERĂ CURSURILE →</HomeButton></div><div className="cm-home-courses__photo"><img alt="Claudia Maria susținând un curs practic pentru cursanți" src={imageUrl} /></div></div></section>
}

export function StudentTestimonials() {
  const testimonials = homepageContent.student_testimonials
  const reviewsUrl = 'https://centrulsakura.ro/cursuri/curs-biodinamica-craniosacrala-miofasciala-modulul-i-16-18-octombrie-2026-bucuresti/#recenzii'
  return <section className="cm-home-section cm-home-student-testimonials"><div className="cm-home-container">
    <div className="cm-home-kicker">Experiențele cursanților</div>
    <h2>Ce spun cei care au învățat alături de mine</h2>
    <div className="cm-home-grid cm-home-grid--three"><TestimonialCards items={testimonials} rich /></div>
    <HomeButton href={reviewsUrl} secondary>VEZI TOATE TESTIMONIALELE CURSANȚILOR →</HomeButton>
  </div></section>
}

export function Newsletter({ body = 'Îți trimit permanent sfaturi utile, noutăți, remedii și tehnici terapeutice, invitații la un webinar sau un anunț despre un eveniment, și multă inspirație pe email.', imageAlt = 'Newsletter Claudia Maria', imageUrl, kicker = 'Newsletter', title = 'Vrei să îți redobândești sănătatea și să previi bolile?' }) {
  return <section className="cm-home-section cm-home-cream" id="newsletter"><div className={`cm-home-container cm-home-newsletter${imageUrl ? '' : ' cm-home-newsletter--without-image'}`}>
    <div><div className="cm-home-kicker">{kicker}</div><h2>{title}</h2><RichText className="cm-home-intro">{body}</RichText></div>
    {imageUrl && <div className="cm-home-newsletter__photo"><img alt={imageAlt} src={imageUrl} /></div>}
  </div></section>
}

export function FinalCallToAction({ bookingUrl, bookingPopupId }) {
  return <section className="cm-home-final" id="contact"><div className="cm-home-container"><h2>Nu trebuie să știi de unde să începi.</h2><p>Vii cu ceea ce simți.<br />De acolo începem, împreună.</p><HomeButton href={bookingUrl} popupId={bookingPopupId}>PROGRAMEAZĂ O CONSULTAȚIE</HomeButton></div></section>
}
