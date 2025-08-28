FROM node:20-alpine AS build-frontend
WORKDIR /app
COPY frontend/package*.json ./
RUN npm ci
COPY frontend/ .
RUN npm run build

FROM nginx:1.25-alpine
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=build-frontend /app/dist/ /usr/share/nginx/html/
