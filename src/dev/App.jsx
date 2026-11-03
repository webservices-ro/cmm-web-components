import React from 'react'
import Homepage from '../Homepage'
import PricingPage from '../PricingPage'
import Header from '../components/home/Header'
import Footer from '../components/home/Footer'

const normalizePath = (path) => {
  const normalized = path.replace(/\/+$/, '')
  return normalized || '/'
}

function NotFound() {
  return <main className="react-page cm-dev-not-found">
    <h1>Pagina nu a fost găsită</h1>
    <a href="/">Înapoi la componente</a>
  </main>
}

function Preview({ children }) {
  return <>
    <a className="cm-dev-back" href="/" aria-label="Înapoi la biblioteca de componente">← ÎNAPOI</a>
    {children}
  </>
}

function ComponentLibrary() {
  return <main className="cm-dev-library">
    <header className="cm-dev-library__hero">
      <div className="cm-dev-library__container">
        <p className="cm-dev-library__eyebrow">Claudia Maria</p>
        <h1>React component library</h1>
        <p>Previzualizează paginile și componentele înainte de publicarea lor în WordPress.</p>
      </div>
    </header>

    <section className="cm-dev-library__content" aria-labelledby="cm-pages-title">
      <div className="cm-dev-library__container">
        <div className="cm-dev-library__heading">
          <div>
            <p className="cm-dev-library__eyebrow">Pagini disponibile</p>
            <h2 id="cm-pages-title">Alege o previzualizare</h2>
          </div>
          <span className="cm-dev-library__count">4 componente</span>
        </div>

        <div className="cm-dev-library__grid">
          <a className="cm-dev-library__card" href="/homepage">
            <span className="cm-dev-library__card-number">01</span>
            <div><h3>Homepage</h3><p>Prezentare, servicii, proces, resurse, cursuri și header responsive.</p></div>
            <span className="cm-dev-library__arrow" aria-hidden="true">→</span>
          </a>
          <a className="cm-dev-library__card" href="/header">
            <span className="cm-dev-library__card-number">02</span>
            <div>
              <h3>Header</h3>
              <p>Navigație desktop, meniu mobil și buton de programare.</p>
            </div>
            <span className="cm-dev-library__arrow" aria-hidden="true">→</span>
          </a>
          <a className="cm-dev-library__card" href="/preturi">
            <span className="cm-dev-library__card-number">03</span>
            <div>
              <h3>Prețuri &amp; servicii</h3>
              <p>Consultații, terapii, programe de loialitate și informații importante.</p>
            </div>
            <span className="cm-dev-library__arrow" aria-hidden="true">→</span>
          </a>
          <a className="cm-dev-library__card" href="/footer">
            <span className="cm-dev-library__card-number">04</span>
            <div>
              <h3>Footer</h3>
              <p>Brand, navigație, contact, rețele sociale și informații legale.</p>
            </div>
            <span className="cm-dev-library__arrow" aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </section>
  </main>
}

export default function App() {
  const path = normalizePath(window.location.pathname)

  if (path === '/') {
    return <ComponentLibrary />
  }

  if (path === '/preturi') {
    return <Preview><PricingPage
      bookingAction={import.meta.env.VITE_DEV_BOOKING_ACTION || 'popup'}
      bookingUrl="#cm-programare"
      contactUrl="#contact"
      natureUrl="#natura"
      whatsappNumber={import.meta.env.VITE_DEV_WHATSAPP_NUMBER || ''}
    /></Preview>
  }

  if (path === '/homepage') {
    return <Preview><Homepage bookingUrl="#contact" coursesUrl="#cursuri" natureUrl="#natura" therapiesUrl="#terapii" /></Preview>
  }

  if (path === '/header') {
    return <Preview><div className="cm-home cm-home--header-only"><Header bookingUrl="/contact/" pricingUrl="/preturi/" /></div></Preview>
  }

  if (path === '/footer') {
    return <Preview><Footer showPaymentInfo social={{ facebook: '#facebook', instagram: '#instagram', youtube: '#youtube' }} /></Preview>
  }

  return <NotFound />
}
