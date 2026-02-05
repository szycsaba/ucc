import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";
import fs from "node:fs";

export default defineConfig({
  plugins: [react(), tailwindcss()],
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    https: {
      key: fs.readFileSync("/ssl/server.key"),
      cert: fs.readFileSync("/ssl/server.crt"),
    },
    hmr: {
      protocol: "wss",
      host: "localhost",
      clientPort: 5173,
    },
    watch: {
      usePolling: true,
      interval: 100,
    },
  },
});
