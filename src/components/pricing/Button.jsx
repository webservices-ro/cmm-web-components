import React from 'react'

export default function Button({ children, href, popupId, secondary = false }) {
  const variant = secondary ? 'secondary' : 'primary'
  const openPopup = (event) => {
    const popupModule = window.elementorProFrontend?.modules?.popup
    const normalizedPopupId = Number.parseInt(popupId, 10)

    if (!Number.isInteger(normalizedPopupId) || normalizedPopupId <= 0 || !popupModule?.showPopup) {
      return
    }

    event.preventDefault()
    popupModule.showPopup({ id: normalizedPopupId })
  }

  const isWhatsapp = /^https:\/\/wa\.me\//i.test(href || '')
  return <a className={`cm-button cm-button--${variant}`} href={href} onClick={openPopup} rel={isWhatsapp ? 'noopener noreferrer' : undefined} target={isWhatsapp ? '_blank' : undefined}>{children}</a>
}
