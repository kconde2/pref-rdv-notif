
# PREF RDV NOTIF Index

Simple PREF RDV NOTIF Index application with Symfony 5

## Requirements

- docker
- docker-compose
- make
- git

## Installation

```shell
cp .env.example .env
```

```shell
make up
```

## Useful commands

- docker-compose exec apache bin/console app:appointment:check

## Usefull links

- APP URL : [http://rdv.localhost/](http://rdv.localhost/)

## Docker dependencies

- Aapache
- PHP
- Mysql
- phpMyAdmin

## Usage

- Change `APP_APPOINTMENT_URL` to fit your situation
- Put the email `APP_APPOINTMENT_RECEIVERS` you want to receive notifications
- Change `MAILER_DSN` to fit your need
