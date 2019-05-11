<img src="/public/images/preview.png" alt="Laravuetify preview image"/>

[![NPM version][npm-image]][npm-url]

# About

Laravuetify is a ready to use Laravel app with VueJS, Vuetify, VueRouter & Vuex. It also uses jwt-auth for user authentication: https://jwt-auth.readthedocs.io/en/develop/

## Get Started

### Clone the repository

Or just download the zip file and proceed to next step

```sh
$ git clone https://github.com/alexela8882/laravuetify.git
```

### Composer & npm

`cd` into root folder of the project and run this command to install all dependencies

```sh
$ composer install
$ npm install
```

## Configure Backend

Cloning this project won't provide you a `.env` file. You can create using this command:

```sh
$ php -r "copy('.env.example', '.env');"
```

### Generate key

```sh
$ php artisan key:generate
$ php artisan jwt:secret
```

### Migrate & Seed DB

You can modify the default roles & permissions in the seeder

```sh
$ php artisan migrate
$ php artisan db:seed
```

Lastly

```sh
$ npm run dev
$ php artisan serve
```

All Done!

You can now visit your website in [http://localhost:8000](http://localhost:8000).

# License

[MIT](https://github.com/alexela8882/laravuetify/LICENSE)