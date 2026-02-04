FROM httpd:2.4

COPY entrypoint.sh /usr/local/bin/ucc-apache-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/ucc-apache-entrypoint.sh \
  && chmod +x /usr/local/bin/ucc-apache-entrypoint.sh

CMD ["/usr/local/bin/ucc-apache-entrypoint.sh"]

