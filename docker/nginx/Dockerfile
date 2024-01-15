FROM nginx:1.21.6

COPY ./nginx.conf /etc/nginx/
COPY ./proxy_params /etc/nginx/proxy_params
COPY ./sites-enabled /etc/nginx/sites-enabled

RUN echo "upstream php-upstream { server ml-backend-php:9000; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
EXPOSE 443
