import React from 'react'

export default function HomeButton({ children, href, popupId, secondary = false, className = '' }) {
  const openPopup = (event) => {
    const popupModule = window.elementorProFrontend?.modules?.popup
    const normalizedPopupId = Number.parseInt(popupId, 10)

    if (!Number.isInteger(normalizedPopupId) || normalizedPopupId <= 0 || !popupModule?.showPopup) {
      return
    }

    event.preventDefault()
    popupModule.showPopup({ id: normalizedPopupId })
  }

  const variant = secondary ? 'cm-home-button--secondary' : 'cm-home-button--primary'

  return <a className={`cm-home-button ${variant}${className ? ` ${className}` : ''}`} href={href} onClick={openPopup}>{children}</a>
}
