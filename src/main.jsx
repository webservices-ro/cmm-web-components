import React from 'react'
import { createRoot } from 'react-dom/client'
import Homepage from './Homepage'
import PricingPage from './PricingPage'
import Header, { defaultNavigation } from './components/home/Header'
import Footer, { defaultFooterLegalLinks, defaultFooterNavigation, defaultPaymentMethods } from './components/home/Footer'
import { Newsletter } from './components/home/sections'
import './global.css'
import './style.css'

const mountedHeaders = new WeakSet()
const mountedFooters = new WeakSet()
const mountedHomepages = new WeakSet()
const mountedNewsletters = new WeakSet()
const mountedPricings = new WeakSet()
const parseNavigation = (value) => {
  if (!value) return defaultNavigation
  try {
    const parsed = JSON.parse(value)
    return Array.isArray(parsed) ? parsed : defaultNavigation
  } catch {
    return defaultNavigation
  }
}

const parseLinks = (value, fallback) => {
  if (!value) return fallback
  try {
    const parsed = JSON.parse(value)
    return Array.isArray(parsed) ? parsed : fallback
  } catch {
    return fallback
  }
}

const mountHeaders = (scope = document) => {
  const elements = scope.matches?.('[data-cm-header-root]') ? [scope] : scope.querySelectorAll?.('[data-cm-header-root]') || []
  elements.forEach((element) => {
    if (mountedHeaders.has(element)) return
    mountedHeaders.add(element)
    createRoot(element).render(<div className="cm-home cm-home--header-only"><Header accountLabel={element.dataset.accountLabel} accountUrl={element.dataset.accountUrl} bookingLabel={element.dataset.bookingLabel} bookingUrl={element.dataset.bookingUrl} bookingPopupId={element.dataset.bookingPopupId} cartCount={Number.parseInt(element.dataset.cartCount, 10) || 0} cartDrawer={element.dataset.cartDrawer !== 'false'} cartUrl={element.dataset.cartUrl} homeUrl={element.dataset.homeUrl} isLoggedIn={element.dataset.isLoggedIn === 'true'} loginLabel={element.dataset.loginLabel} loginUrl={element.dataset.loginUrl} logoImageUrl={element.dataset.logoImageUrl} logoText={element.dataset.logoText} logoutLabel={element.dataset.logoutLabel} logoutUrl={element.dataset.logoutUrl} miniCartHtml={element.dataset.miniCartHtml} navigation={parseNavigation(element.dataset.navigation)} pricingUrl={element.dataset.pricingUrl} showAccount={element.dataset.showAccount !== 'false'} showBooking={element.dataset.showBooking !== 'false'} showCart={element.dataset.showCart !== 'false'} showLogoDesktop={element.dataset.showLogoDesktop === 'true'} sticky={element.dataset.sticky !== 'false'} whatsappMessage={element.dataset.whatsappMessage} whatsappNumber={element.dataset.whatsappNumber} /></div>)
  })
}

mountHeaders()

const mountFooters = (scope = document) => {
  const elements = scope.matches?.('[data-cm-footer-root]') ? [scope] : scope.querySelectorAll?.('[data-cm-footer-root]') || []
  elements.forEach((element) => {
    if (mountedFooters.has(element)) return
    mountedFooters.add(element)
    createRoot(element).render(<Footer brand={element.dataset.brand} bridge={element.dataset.bridge} contactHeading={element.dataset.contactHeading} contactText={element.dataset.contactText} copyright={element.dataset.copyright} email={element.dataset.email} exploreHeading={element.dataset.exploreHeading} homeUrl={element.dataset.homeUrl} legalLinks={parseLinks(element.dataset.legalLinks, defaultFooterLegalLinks)} logoImageUrl={element.dataset.logoImageUrl} navigation={parseLinks(element.dataset.navigation, defaultFooterNavigation)} paymentHeading={element.dataset.paymentHeading} paymentImages={parseLinks(element.dataset.paymentImages, [])} paymentMethods={parseLinks(element.dataset.paymentMethods, defaultPaymentMethods)} paymentNote={element.dataset.paymentNote} paymentScript={element.dataset.paymentScript} paymentText={element.dataset.paymentText} phone={element.dataset.phone} showPaymentInfo={element.dataset.showPaymentInfo === 'true'} social={{ facebook: element.dataset.facebookUrl, instagram: element.dataset.instagramUrl, youtube: element.dataset.youtubeUrl }} tagline={element.dataset.tagline} />)
  })
}

mountFooters()

const mountHomepages = (scope = document) => {
  const elements = scope.matches?.('[data-cm-homepage-root]') ? [scope] : scope.querySelectorAll?.('[data-cm-homepage-root]') || []
  elements.forEach((element) => {
    if (mountedHomepages.has(element)) return
    mountedHomepages.add(element)
    createRoot(element).render(<Homepage aboutImageUrl={element.dataset.aboutImageUrl || undefined} bookingUrl={element.dataset.bookingUrl} bookingPopupId={element.dataset.bookingPopupId} coursesImageUrl={element.dataset.coursesImageUrl || undefined} coursesUrl={element.dataset.coursesUrl} heroImageUrl={element.dataset.heroImageUrl || undefined} manualImageUrl={element.dataset.manualImageUrl || undefined} natureUrl={element.dataset.natureUrl} raindropImageUrl={element.dataset.raindropImageUrl || undefined} reflexologyImageUrl={element.dataset.reflexologyImageUrl || undefined} ritualImageUrl={element.dataset.ritualImageUrl || undefined} therapiesUrl={element.dataset.therapiesUrl} />)
  })
}

mountHomepages()

const mountNewsletters = (scope = document) => {
  const elements = scope.matches?.('[data-cm-newsletter-root]') ? [scope] : scope.querySelectorAll?.('[data-cm-newsletter-root]') || []
  elements.forEach((element) => {
    if (mountedNewsletters.has(element)) return
    mountedNewsletters.add(element)
    createRoot(element).render(<div className="cm-home cm-home--elementor"><Newsletter body={element.dataset.body} imageAlt={element.dataset.imageAlt} imageUrl={element.dataset.imageUrl || undefined} kicker={element.dataset.kicker} title={element.dataset.title} /></div>)
  })
}

mountNewsletters()

const mountPricings = (scope = document) => {
  const elements = scope.matches?.('[data-cm-pricing-root]') ? [scope] : scope.querySelectorAll?.('[data-cm-pricing-root]') || []
  elements.forEach((element) => {
    if (mountedPricings.has(element)) return
    mountedPricings.add(element)
    createRoot(element).render(<PricingPage bookingAction={element.dataset.bookingAction} bookingUrl={element.dataset.bookingUrl} bookingPopupId={element.dataset.bookingPopupId} contactUrl={element.dataset.contactUrl} natureUrl={element.dataset.natureUrl} useModuleStyling={element.dataset.useModuleStyling === 'true'} whatsappGenericMessage={element.dataset.whatsappGenericMessage} whatsappInitialMessage={element.dataset.whatsappInitialMessage} whatsappLoyaltyMessage={element.dataset.whatsappLoyaltyMessage} whatsappNumber={element.dataset.whatsappNumber} whatsappOnlineMessage={element.dataset.whatsappOnlineMessage} />)
  })
}

mountPricings()

const registerElementorMounts = () => {
  const hooks = window.elementorFrontend?.hooks
  if (!hooks) return

  hooks.addAction('frontend/element_ready/cm-header.default', ($scope) => {
    mountHeaders($scope?.[0] || $scope)
  })
  hooks.addAction('frontend/element_ready/cm-footer.default', ($scope) => {
    mountFooters($scope?.[0] || $scope)
  })
  hooks.addAction('frontend/element_ready/cm-homepage.default', ($scope) => {
    mountHomepages($scope?.[0] || $scope)
  })
  hooks.addAction('frontend/element_ready/cm-newsletter.default', ($scope) => {
    mountNewsletters($scope?.[0] || $scope)
  })
}

if (window.elementorFrontend?.hooks) {
  registerElementorMounts()
} else {
  window.addEventListener('elementor/frontend/init', registerElementorMounts, { once: true })
}
