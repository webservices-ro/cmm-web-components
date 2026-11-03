import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  build: {
    manifest: true,
    outDir: 'wordpress-plugin/claudia-maria-wp-react/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: 'src/main.jsx',
      output: {
        // WordPress page caches can outlive a plugin build. Stable bundle names
        // prevent cached HTML from pointing at deleted hash-named JS/CSS files.
        entryFileNames: 'assets/main.js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name][extname]',
      },
    },
  },
})
