import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

// https://vite.dev/config/
// path alias, in this file and in tsconfig.app.json: https://stackoverflow.com/questions/78567007/how-do-i-solve-the-error-failed-to-resolve-import-src-lib-utils-from-does
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
  server: {
    open: true,
  },
})
