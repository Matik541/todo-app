# Aplikacja "To-Do List" (Laravel 11)

Aplikacja typu "To-Do List" umożliwiająca zarządzanie zadaniami, uwierzytelnianie użytkowników, wysyłanie powiadomień e-mail oraz opcjonalne funkcje takie jak historia edycji.

## Wymagania

* PHP >= 8.2
* Composer
* SQLite
* Node.js i npm (do kompilacji zasobów front-end)
* Web server (wbudowany serwer Laravela)

## Instalacja i uruchomienie

1.  **Sklonuj repozytorium:**

    ```bash
    git clone [https://github.com/Matik541/todo-app.git](https://github.com/Matik541/todo-app.git)
    cd todo-app
    ```

    Jeśli pobrałeś jako ZIP, rozpakuj do `C:\xampp\htdocs\todo-app`.

2.  **Zainstaluj zależności Composer:**

    Otwórz wiersz poleceń (CMD/PowerShell) w katalogu `todo-app` (np. `C:\xampp\htdocs\todo-app`).

    ```bash
    composer install
    ```

3.  **Skopiuj plik `.env`:**

    ```bash
    copy .env.example .env
    ```

4.  **Wygeneruj klucz aplikacji:**

    ```bash
    php artisan key:generate
    ```

5.  **Konfiguracja bazy danych:**

    Otwórz plik `.env` i zaktualizuj dane dostępowe do bazy danych:

    **SQLite (domyślnie):**

    ```dotenv
    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```
    Jeśli używasz SQLite, upewnij się, że plik `database/database.sqlite` istnieje. Jeśli nie, utwórz go ręcznie

6.  **Uruchom migracje bazy danych:**

    ```bash
    php artisan migrate
    ```

7.  **Zainstaluj zależności Node.js i skompiluj zasoby front-end:**

    ```bash
    npm install
    npm run dev 
    ```
    lub (do jednorazowej kompilacji)
    ``` 
    npm install
    npm run build
    ```
    

8.  **Skonfiguruj pocztę e-mail (dla powiadomień):**

    W pliku `.env` ustaw dane swojego serwera SMTP lub użyj Mailtrap do testów.

    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=mailpit # lub smtp.mailtrap.io
    MAIL_PORT=1025 # lub 2525
    MAIL_USERNAME=null # Twój username Mailtrap
    MAIL_PASSWORD=null # Twoje hasło Mailtrap
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    Następnie ustaw połączenie kolejek na `database` w `.env`:
    ```dotenv
    QUEUE_CONNECTION=database
    ```

9.  **Uruchom Workera Kolejek (wymagane dla powiadomień e-mail):**

    Otwórz **nowy** wiersz poleceń i uruchom:

    ```bash
    php artisan queue:work
    ```
    Ten proces powinien być uruchomiony stale w tle.

10. **Skonfiguruj Harmonogram Zadań (Scheduler) w Windows:**

    Aby powiadomienia e-mail o terminach zadań były wysyłane, musisz skonfigurować zadanie w Harmonogramie Zadań Windows.

    * Otwórz "Harmonogram zadań" (Task Scheduler).
    * Wybierz "Utwórz zadanie podstawowe..." (Create Basic Task...).
    * **Nazwa:** Laravel Scheduler
    * **Wyzwalacz:** Codziennie (Daily)
    * **Akcja:** Uruchom program (Start a program)
    * **Program/skrypt:** `C:\xampp\php\php.exe` (Dostosuj ścieżkę do Twojej instalacji PHP w XAMPP)
    * **Dodaj argumenty (opcjonalnie):** `C:\xampp\htdocs\todo-app\artisan schedule:run` (Dostosuj ścieżkę do Twojego projektu)
    * **Katalog początkowy:** `C:\xampp\htdocs\todo-app` (Dostosuj ścieżkę do Twojego projektu)
    * Zakończ.
    To zadanie powinno być skonfigurowane tak, aby uruchamiało się co minutę, jednak Scheduler Laravela (`dailyAt('08:00')`) zadba o to, by nasza komenda przypominająca uruchomiła się tylko raz dziennie o 8:00.

11. **Uruchom serwer deweloperski Laravela:**

    W głównym wierszu poleceń (w katalogu projektu `todo-app`):

    ```bash
    php artisan serve
    ```

    Aplikacja będzie dostępna pod adresem: `http://127.0.0.1:8000`

## Funkcjonalności

* **CRUD zadań:** Dodawanie, przeglądanie, edytowanie i usuwanie zadań.
* **Pola zadań:** Nazwa, opis (opcjonalnie), priorytet (niski/średni/wysoki), status (do zrobienia/w toku/wykonane), termin wykonania.
* **Uwierzytelnianie:** Rejestracja i logowanie użytkowników (każdy użytkownik zarządza własnymi zadaniami).
* **Filtrowanie:** Listy zadań według priorytetu, statusu i terminu wykonania.
* **Powiadomienia e-mail:** Przypomnienia o zadaniach na 1 dzień przed terminem (przez Laravel Queues i Scheduler).
* **Udostępnianie zadań:** Generowanie linków publicznych z tokenem dostępu, które wygasają po 24 godzinach.
* **Historia edycji (Opcjonalnie):** Zapisywanie każdej zmiany w zadaniu, z możliwością przeglądania poprzednich wersji.
