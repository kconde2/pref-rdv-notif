FROM php:7.3-apache

RUN apt-get update && apt-get install -y \
  git \
  zip \
  libicu-dev \
  libzip-dev \
  wget \
  gnupg2 \
  libnss3 \
  libasound2 \
  fonts-liberation \
  libappindicator3-1 \
  xdg-utils \
  lsb-release \
  libxss1 \
  libu2f-udev \
  libvulkan1 \
  libgbm1 \
  libxslt-dev \
  tzdata \
  cron \
  supervisor \
  && rm -rf /var/lib/apt/lists/*

# Timezone
RUN cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && \
  echo "Europe/Paris" > /etc/timezone

RUN rm -rf /var/cache/apk/*

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install pdo pdo_mysql zip opcache xsl
RUN docker-php-ext-enable opcache

COPY --from=composer:1.8.6 /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

# Install Chrome for Panther
ENV CHROME_VERSION=80.0.3987.100-1
RUN curl -LO http://mirror.cs.uchicago.edu/google-chrome/pool/main/g/google-chrome-stable/google-chrome-stable_${CHROME_VERSION}_amd64.deb
RUN apt-get install -y ./google-chrome-stable_${CHROME_VERSION}_amd64.deb
RUN rm google-chrome-stable_${CHROME_VERSION}_amd64.deb

# Set panther env variable to run in docker
ENV PANTHER_NO_SANDBOX=1

# |--------------------------------------------------------------------------
# | Supercronic
# |--------------------------------------------------------------------------
# |
# | Supercronic is a drop-in replacement for cron (for containers).
# |

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.1.11/supercronic-linux-amd64 \
  SUPERCRONIC=supercronic-linux-amd64 \
  SUPERCRONIC_SHA1SUM=a2e2d47078a8dafc5949491e5ea7267cc721d67c

RUN curl -fsSLO "$SUPERCRONIC_URL" \
  && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
  && chmod +x "$SUPERCRONIC" \
  && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
  && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

WORKDIR /var/www/symfony

CMD ["/usr/bin/supervisord"]
