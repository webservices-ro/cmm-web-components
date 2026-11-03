import { readFileSync } from 'node:fs'
import { resolve } from 'node:path'
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

const moduleConfig = JSON.parse(readFileSync(new URL('./module.config.json', import.meta.url), 'utf8'))

export default defineConfig({
  plugins: [react()],
  build: {
    manifest: true,
    outDir: resolve('wordpress-plugin', moduleConfig.slug, 'dist'),
    emptyOutDir: true,
    rollupOptions: {
      input: 'src/main.jsx',
      output: {
        entryFileNames: 'assets/main.js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name][extname]',
      },
    },
  },
})
