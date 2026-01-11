FROM --platform=linux/x86_64 nginx:1.23-alpine

COPY default.conf /etc/nginx/conf.d/default.conf.template
COPY nginx.conf /etc/nginx/nginx.conf

COPY docker-entrypoint.sh /

RUN ["chmod", "+x", "/docker-entrypoint.sh"]

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]
