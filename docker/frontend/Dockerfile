FROM node:latest

RUN apt-get update -o Acquire::Retries=3 \
  && apt-get install -y --no-install-recommends \
    ca-certificates \
    git \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY entrypoint.sh /usr/local/bin/ucc-frontend-entrypoint.sh
RUN chmod +x /usr/local/bin/ucc-frontend-entrypoint.sh

EXPOSE 5173

CMD ["/usr/local/bin/ucc-frontend-entrypoint.sh"]

