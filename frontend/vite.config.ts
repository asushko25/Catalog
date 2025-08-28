import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react-swc'

const REPO_BASE = '/Catalog/'

export default defineConfig(() => {
  const isCI = process.env.GITHUB_ACTIONS === 'true'
  return {
    plugins: [react()],
    base: isCI ? REPO_BASE : '/',
    server: {
      port: 3000,
      open: true,
      proxy: {
        '/api': {
          target: 'http://127.0.0.1:8000',
          changeOrigin: true,
          secure: false,
        },
      },
    },
  }
})
