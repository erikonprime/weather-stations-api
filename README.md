# Simple Weather Stations API

## Description
A simple web service that provides information about weather stations in Latvia, 
built with **Symfony 7**, **Nginx**, and **Docker**.

## Getting Started

### Dependencies

- An API security token (`API_SECURITY_TOKEN`)

### Installation

1. **Clone the repository:**
    ```bash
    git clone git@github.com:erikonprime/weather-stations-api.git
    cd weather-stations-api
    ```

2. **Set up the environment variable:**
    - Add your `API_SECURITY_TOKEN` to your `.env` file (e.g.):
     ```
      API_SECURITY_TOKEN=804d777bbb7cfa296adfb72cc73ee425dbabd08f997501bec8523e93a71cabca
      ```

3. **Build and start the containers:**
    ```bash
    docker compose build --no-cache
    docker compose up -d --force-recreate
    ```

4. **Install Composer dependencies (inside PHP container):**
    ```bash
    docker exec -it weather-stations-php bash
    composer install
    ```

5. **Your API is available at:**  
   [http://localhost:8080/](http://localhost:8080/)

6. **API Documentation:**  
   [http://localhost:8080/api/doc](http://localhost:8080/api/doc)

### Tips & Tricks

- **Make bundle (generate code):**
    ```bash
    php bin/console make:controller --help
    ```

- **Useful documentation:**
    - [Custom Symfony Security Authenticator](https://symfony.com/doc/current/security/custom_authenticator.html)
    - [NelmioApiDocBundle](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html)
