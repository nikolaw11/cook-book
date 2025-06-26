# Książka kucharska

## Wymagania

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/install)

## Instalacja i uruchomienie

1. Sklonuj repozytorium:

    ```bash
    git clone https://github.com/nikolaw11/cook-book.git
    ```

2. Zbuduj i uruchom kontenery Docker:

    ```bash
    docker-compose up -d --build
    ```

3. Wejdź do kontenera PHP:

    ```bash
    docker-compose exec php bash
    ```

4. Wejdź do katalogu app:

   ```bash
   cd app
   ```
   
5. Zainstaluj zależności PHP:

    ```bash
    composer install
    ```

6. Skonfiguruj plik `.env` z danymi do bazy danych:

    ```
    DATABASE_URL="mysql://username:password@host:port/database?serverVersion=8.0"
    ```

7. Utwórz bazę danych, wykonaj migracje i załaduj przykładowe dane:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    ```

8. Aplikacja będzie dostępna pod adresem:

    ```
    http://localhost:8000
    ```

## Troubleshooting

* **2024.05.11 - ERROR: for apache  'ContainerConfig'**

  Error `ERROR: for apache  'ContainerConfig'` after `docker-compose up -d` execution can be solved `docker compose up -d --force-recreate`

  


