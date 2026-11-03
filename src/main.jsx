import { createRoot } from 'react-dom/client'
import App from './App.jsx'
import moduleConfig from '../module.config.json'
import './style.css'

document.querySelectorAll(`[data-${moduleConfig.slug}-root]`).forEach((element) => {
  createRoot(element).render(<App />)
})
