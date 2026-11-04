import { createRoot } from 'react-dom/client'
import Router from './Router.jsx'
import moduleConfig from '../module.config.json'
import './style.css'

document.querySelectorAll(`[data-${moduleConfig.slug}-root]`).forEach((element) => {
  let config = {}

  try {
    config = JSON.parse(element.dataset.config || '{}')
  } catch (error) {
    console.error('Ateliere terapeutice: configurația paginii nu este validă.', error)
  }

  createRoot(element).render(<Router config={config} />)
})
