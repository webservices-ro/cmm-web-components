import React, { useEffect, useId, useState } from 'react'
import SafeHtml from './SafeHtml'

export const defaultNavigation = [
  { label: 'Home', route: '/' },
  { label: 'Cursuri', route: '/cursuri/' },
  { label: 'Programe', route: '/programe/' },
  { label: 'Servicii', route: '/servicii/', children: [
    { label: 'Masaje', route: '/masaje/' },
    { label: 'Osteoterapii', route: '/osteoterapii/' },
    { label: 'Terapii medicale alternative', route: '/terapii-medicale-alternative/' },
  ] },
  { label: 'Despre mine', route: '/despre-mine/', children: [
    { label: 'Certificări', route: '/certificari/' },
  ] },
  { label: 'Resurse', route: '/resurse/' },
  { label: 'Testimoniale', route: '/testimoniale/' },
  { label: 'Blog', route: '/blog/' },
  { label: 'Prețuri', route: '/preturi/' },
  { label: 'Contact', route: '/contact/' },
]

export default function Header({ accountLabel = 'Contul meu', accountUrl = '/contul-meu/', bookingLabel = 'PROGRAMEAZĂ', bookingUrl, bookingPopupId, cartCount = 0, cartDrawer = true, cartUrl = '/cos/', homeUrl = '/', isLoggedIn = false, loginLabel = 'Login', loginUrl = '/contul-meu/', logoImageUrl, logoText = 'CLAUDIA MARIA', logoutLabel = 'Logout', logoutUrl = '/', miniCartHtml = '', navigation = defaultNavigation, pricingUrl = '/preturi/', showAccount = true, showBooking = true, showCart = true, showLogoDesktop = false, sticky = true, whatsappMessage = 'Bună, Claudia! Am văzut site-ul și aș dori să mă programez. Îmi poți spune ce disponibilități ai?', whatsappNumber = '' }) {
  const [isOpen, setIsOpen] = useState(false)
  const [isCartOpen, setIsCartOpen] = useState(false)
  const [openSubmenu, setOpenSubmenu] = useState(null)
  const navigationId = useId()
  const siteBase = homeUrl === '/' ? '' : homeUrl.replace(/\/$/, '')
  const resolveUrl = (url) => /^(?:[a-z]+:|#)/i.test(url || '') ? url : `${siteBase}${url || '#'}`
  const resolveRoute = (item) => navigation === defaultNavigation && item.label === 'Prețuri' ? resolveUrl(pricingUrl) : resolveUrl(item.route)
  const normalizedWhatsappNumber = String(whatsappNumber).replace(/\D/g, '')
  const whatsappUrl = normalizedWhatsappNumber ? `https://wa.me/${normalizedWhatsappNumber}?text=${encodeURIComponent(whatsappMessage)}` : ''
  const closeNavigation = () => {
    setIsOpen(false)
    setOpenSubmenu(null)
  }

  useEffect(() => {
    if (!isOpen) return undefined

    const closeOnEscape = (event) => {
      if (event.key === 'Escape') setIsOpen(false)
    }

    window.addEventListener('keydown', closeOnEscape)
    return () => window.removeEventListener('keydown', closeOnEscape)
  }, [isOpen])

  useEffect(() => {
    if (!isCartOpen) return undefined
    const previousOverflow = document.body.style.overflow
    const closeOnEscape = (event) => {
      if (event.key === 'Escape') setIsCartOpen(false)
    }
    document.body.style.overflow = 'hidden'
    window.addEventListener('keydown', closeOnEscape)
    return () => {
      document.body.style.overflow = previousOverflow
      window.removeEventListener('keydown', closeOnEscape)
    }
  }, [isCartOpen])

  const openBooking = (event) => {
    const popupModule = window.elementorProFrontend?.modules?.popup
    if (!bookingPopupId || !popupModule?.showPopup) return
    event.preventDefault()
    popupModule.showPopup({ id: Number(bookingPopupId) })
    closeNavigation()
  }

  const openCart = (event) => {
    closeNavigation()
    if (!cartDrawer || !window.matchMedia('(min-width: 1024px)').matches) return
    event.preventDefault()
    setIsCartOpen(true)
  }

  return <header className={`cm-home-header${sticky ? '' : ' cm-home-header--not-sticky'}${showAccount ? '' : ' cm-home-header--without-account'}${showLogoDesktop ? ' cm-home-header--desktop-logo' : ''}`}>
    {showAccount && <div className="cm-home-account-bar">
      <div className="cm-home-container cm-home-account-bar__inner">
        {isLoggedIn ? <><a href={logoutUrl}>{logoutLabel}</a><a href={accountUrl}>{accountLabel}</a></> : <a className="xoo-el-login-tgr" href={loginUrl}>{loginLabel}</a>}
      </div>
    </div>}
    <div className="cm-home-container cm-home-header__inner">
      <a className="cm-home-logo" href={`${siteBase}/`}>{logoImageUrl ? <img alt={logoText} src={logoImageUrl} /> : logoText}</a>
      <button
        aria-controls={navigationId}
        aria-expanded={isOpen}
        aria-label={isOpen ? 'Închide meniul' : 'Deschide meniul'}
        className="cm-home-menu-toggle"
        onClick={() => setIsOpen((open) => !open)}
        type="button"
      >
        <span /><span /><span />
      </button>
      <nav className={`cm-home-nav${isOpen ? ' is-open' : ''}`} id={navigationId} aria-label="Navigație principală">
        {navigation.map((item, index) => item.children?.length ? <div className={`cm-home-nav__item has-children${openSubmenu === item.label ? ' is-open' : ''}`} key={`${item.label}-${index}`}>
          <div className="cm-home-nav__row">
            <a className="cm-home-nav__link" href={resolveRoute(item)} onClick={closeNavigation}>{item.label}</a>
            <button aria-expanded={openSubmenu === item.label} aria-label={`${openSubmenu === item.label ? 'Închide' : 'Deschide'} submeniul ${item.label}`} className="cm-home-submenu-toggle" onClick={() => setOpenSubmenu((current) => current === item.label ? null : item.label)} type="button"><svg aria-hidden="true" viewBox="0 0 12 8"><path d="m1 1 5 5 5-5" /></svg></button>
          </div>
          <div className="cm-home-submenu">{item.children.map((child) => <a href={resolveUrl(child.route)} key={child.label} onClick={closeNavigation}>{child.label}</a>)}</div>
        </div> : <a className="cm-home-nav__link" href={resolveRoute(item)} key={`${item.label}-${index}`} onClick={closeNavigation}>{item.label}</a>)}
        {showCart && <a aria-expanded={cartDrawer ? isCartOpen : undefined} aria-haspopup={cartDrawer ? 'dialog' : undefined} aria-label={`Coș${cartCount > 0 ? `, ${cartCount} produse` : ''}`} className="cm-home-cart" href={cartUrl} onClick={openCart}>
          <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20.5 8H6.1M9.5 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" /></svg>
          {cartCount > 0 && <span>{cartCount}</span>}
        </a>}
        {showBooking && <a className="cm-home-button cm-home-button--primary" href={whatsappUrl || bookingUrl || '#'} onClick={whatsappUrl ? closeNavigation : openBooking} rel={whatsappUrl ? 'noopener noreferrer' : undefined} target={whatsappUrl ? '_blank' : undefined}>{bookingLabel}</a>}
      </nav>
    </div>
    {cartDrawer && <>
      <button aria-label="Închide coșul" className={`cm-cart-drawer__overlay${isCartOpen ? ' is-open' : ''}`} onClick={() => setIsCartOpen(false)} tabIndex={isCartOpen ? 0 : -1} type="button" />
      <aside aria-hidden={!isCartOpen} aria-label="Coș de cumpărături" aria-modal="true" className={`cm-cart-drawer${isCartOpen ? ' is-open' : ''}`} role="dialog">
        <div className="cm-cart-drawer__header"><h2>Coșul tău</h2><button aria-label="Închide coșul" onClick={() => setIsCartOpen(false)} type="button">×</button></div>
        <div className="cm-cart-drawer__content widget_shopping_cart_content"><SafeHtml>{miniCartHtml}</SafeHtml></div>
      </aside>
    </>}
  </header>
}
