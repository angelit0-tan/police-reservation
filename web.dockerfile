FROM nginx:1.29
COPY nginx/conf.d /etc/nginx/conf.d/
COPY public /var/www/public