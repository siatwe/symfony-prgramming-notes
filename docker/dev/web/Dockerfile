FROM alpine:3.6

RUN apk add --no-cache --update \
    nginx

RUN addgroup -g 1000 www \
    && adduser -D -u 1000 -G www www

COPY upstream.conf /etc/nginx/conf.d/upstream.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx"]
