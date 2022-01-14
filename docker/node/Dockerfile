FROM node:lts-alpine

ARG DOCKER_GROUP_ID
ARG DOCKER_USER_ID

RUN apk --no-cache add shadow && usermod -u $DOCKER_USER_ID node && groupmod -g $DOCKER_GROUP_ID node

COPY package*.json ./

RUN npm install -g npm@latest

RUN npm i

COPY . .

RUN npm run dev

EXPOSE "3000"

CMD [ "npm", "run", "watch" ]
