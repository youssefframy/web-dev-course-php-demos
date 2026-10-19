# Converts between DBML and MariaDB DDL for ./sql, using @dbml/cli.
FROM node:22.23.3-bookworm-slim
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --omit=dev
COPY server.js ./
EXPOSE 3000
HEALTHCHECK --interval=2s --timeout=2s --retries=20 CMD node -e "fetch('http://localhost:3000/health').then(r => process.exit(r.ok ? 0 : 1)).catch(() => process.exit(1))"
CMD ["node", "server.js"]
