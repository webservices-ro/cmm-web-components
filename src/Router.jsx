import { useEffect, useState } from 'react'
import WorkshopsPage from './App.jsx'
import DaruriSacrePage from './DaruriSacrePage.jsx'

const normalizePath = (path) => path.replace(/\/+$/, '') || '/'

function ComponentIndex() {
  return (
    <main className="component-index">
      <p className="component-index__label">CMM Components</p>
      <h1>Pagini disponibile</h1>
      <div className="component-index__grid">
        <a href="/ateliere-terapeutice">Ateliere terapeutice <span>Vitrină de ateliere</span></a>
        <a href="/daruri-sacre-2026">Daruri Sacre – Ediția 2026 <span>Pagină de prezentare atelier</span></a>
      </div>
    </main>
  )
}

export default function Router({ config = {} }) {
  const configuredPage = config.component || config.page
  const [path, setPath] = useState(() => normalizePath(window.location.pathname))

  useEffect(() => {
    if (configuredPage) return undefined

    const navigate = (event) => {
      const link = event.target.closest('a[href^="/"]')
      if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return
      event.preventDefault()
      window.history.pushState({}, '', link.href)
      setPath(normalizePath(window.location.pathname))
      window.scrollTo({ top: 0, behavior: 'instant' })
    }
    const handlePopState = () => setPath(normalizePath(window.location.pathname))
    document.addEventListener('click', navigate)
    window.addEventListener('popstate', handlePopState)
    return () => {
      document.removeEventListener('click', navigate)
      window.removeEventListener('popstate', handlePopState)
    }
  }, [configuredPage])

  const page = configuredPage || (path === '/daruri-sacre-2026' ? 'daruri-sacre-2026' : path === '/ateliere-terapeutice' ? 'workshops' : 'index')

  if (page === 'daruri-sacre-2026') return <DaruriSacrePage config={config} />
  if (page === 'workshops') return <WorkshopsPage config={config} />
  return <ComponentIndex />
}
