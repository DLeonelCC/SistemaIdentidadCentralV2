# instalar node
FROM node:18-alpine as develop-stage
WORKDIR /app
COPY package*.json ./
COPY . .

# instalar quasar
FROM develop-stage as build-stage
RUN yarn

# costruyendo el app
RUN yarn build

# instalar server
RUN npm install -g http-server

# exponer
EXPOSE 8080
CMD ["http-server", "dist", "-p", "8080"]
