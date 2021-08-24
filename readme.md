# Welcome to masteriyo 👋

![Version](https://img.shields.io/badge/version-0.1.0-blue.svg?cacheSeconds=2592000)
![Prerequisite](https://img.shields.io/badge/node-%3E%3D10.0.0-blue.svg)
![Prerequisite](https://img.shields.io/badge/npm-%3E%3D6.9.0-blue.svg)
[![License: GPL--2.0+](https://img.shields.io/badge/License-GPL--2.0+-yellow.svg)](#)

# Masteriyo

## Prerequisites

- node >=10.0.0
- npm >=6.9.0
- composer >=2.0.0
- yarn >=1.0.1

## Local Development

Clone repo to your `plugins` folder of WordPress installation

```sh
git clone https://github.com/wpeverest/wordpress-lms.git
```

Make sure you have `node`, `yarn` and `composer` installed. Install packages.

Note: Use `yarn` instead of `npm` to generate only one lock file.

```sh
yarn install
composer update
```

This step is only necessary in development mode, make a copy of .env.example as .env
Set the administrator username and password, and set the base wp-json URL.

Once successfully installed you can run Local Development server using. This will open Webpack development server.

```sh
yarn start
```

## Runnig Masteriyo on Dashboard

We can't run development server on WordPress Dashboard. We need to build our app first

```sh
yarn build
```

Now you can use Masteriyo from WordPress Dashboard

## Author

👤 **Masteriyo**

- Website: https://masteriyo.com

## Show your support

Give a ⭐️ if this project helped you!

---
