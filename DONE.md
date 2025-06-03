# Aplikacja "To-Do List" - DONE.md

## Co zostało zrobione:

### 1. Wymagania funkcjonalne:

* **CRUD na zadaniach:**
    * Zaimplementowano pełne operacje Create, Read, Update, Delete dla zadań.
    * Pola: `name` (max 255, wymagane), `description` (opcjonalnie), `priority` (low/medium/high), `status` (to-do/in progress/done), `due_date` (data, wymagane).
    * Użyto `foreignId('user_id')` z `onDelete('cascade')` dla powiązania zadań z użytkownikami.
* **Przeglądanie zadań:**
    * Wdrożono filtrowanie listy zadań według `priority`, `status` i `due_date` w metodzie `index` `TaskController`.
* **Powiadomienia e-mail:**
    * Stworzono `TaskDueDateReminder` Notification, która implementuje `ShouldQueue`.
    * Zaimplementowano Artisan Command `CheckTaskDueDates` do sprawdzania zadań z terminem na następny dzień.
    * Skonfigurowano Laravel Scheduler w `app/Console/Kernel.php` do uruchamiania komendy codziennie o 8:00.
    * Instrukcje konfiguracji `queue:work` i harmonogramu zadań Windows w README.md.
* **Walidacja:**
    * Zastosowano walidację formularzy w `TaskController` za pomocą metody `Request->validate()`, zapewniając sprawdzenie wymaganych pól, formatu daty (`after_or_equal:today`) i limitów znaków. Błędy walidacji są wyświetlane w widokach.
* **Obsługa wielu użytkowników:**
    * Wykorzystano Laravel Breeze do szybkiej implementacji systemu uwierzytelniania.
    * Każde zadanie jest powiązane z `user_id` i wyświetlane są tylko zadania zalogowanego użytkownika.
    * Polityka `TaskPolicy` zapewnia, że użytkownik ma dostęp tylko do swoich zadań (view, update, delete).
* **Udostępnianie zadań bez autoryzacji:**
    * Dodano kolumny `access_token` i `token_expires_at` do tabeli `tasks`.
    * W `TaskController` zaimplementowano metody `generateShareLink` (generowanie tokenu z 24h ważnością) i `showSharedTask` (wyświetlanie zadania po tokenie z weryfikacją ważności).
    * Trasy dla udostępniania są odpowiednio zdefiniowane (jedna prywatna, jedna publiczna).

### 2. Dodatkowe funkcje (Opcjonalne):

* **Pełna historia edycji notatek:**
    * Stworzono model `TaskHistory` i migrację z polami `task_id`, `user_id`, `old_data` (JSON), `new_data` (JSON) i `change_summary`.
    * Zaimplementowano `TaskObserver`, który nasłuchuje na zdarzenie `updated` modelu `Task` i automatycznie tworzy wpis w historii z podsumowaniem zmienionych atrybutów.
    * Historia jest wyświetlana na stronie szczegółów zadania (`tasks.show`).

### 3. Wymagania techniczne:

* **Back-end:**
    * Laravel 11: Cały projekt zbudowany jest na Laravelu 11.
    * REST API: Kontrolery zasobów implementują standardowe operacje REST.
    * Eloquent ORM: Szeroko wykorzystywane do interakcji z bazą danych, włączając relacje (user-tasks, task-history).
    * SQLite: Projekt jest skonfigurowany do pracy z SQLite. Migracje bazy danych zapewniają odpowiednią strukturę.
* **Front-end:**
    * Prosty interfejs użytkownika stworzony w Laravel Blade, wykorzystując TailwindCSS z Laravel Breeze do stylizacji.

### 4. Ogólne:

* **Bezpieczeństwo aplikacji:**
    * Walidacja formularzy, ochrona przed masowym przypisywaniem (`$fillable`), użycie polityk autoryzacji (`TaskPolicy`), ochrona przed CSRF (tokeny `@csrf` w formularzach).
    * Tokeny udostępniania są unikalne i mają ograniczony czas ważności.
* **Struktura i czytelność kodu (SOLID, KISS):**
    * **SOLID:**
        * **SRP:** Kontrolery zarządzają requestami/response, modele logiką danych, polityki autoryzacją, Observer historią zmian.
        * **OCP:** Nowe funkcje (np. Observer, powiadomienia) zostały dodane bez modyfikowania istniejących klas w znaczący sposób.
    * **KISS:**
        * Prosta struktura kontrolerów i widoków.
        * Wykorzystanie gotowych komponentów Laravela (Breeze, Resource Controllers, Notifications, Queues, Scheduler).
        * Wspólny plik formularza (`_form.blade.php`) minimalizujący powtórzenia kodu.
* **Znajomość Laravel:**
    * Efektywne wykorzystanie Eloquent ORM (relacje, mutatory, `$fillable`, `$casts`).
    * Zastosowanie migracji, kontrolerów zasobów, walidacji, powiadomień, kolejek, planisty, polityk i obserwatorów.
* **Obsługa błędów:**
    * Walidacja formularzy automatycznie wyświetla błędy.
    * Obsługa wyjątków w integracji z Google Calendar.
    * Błędy systemowe są domyślnie obsługiwane przez Laravel (strony 404, 500).

## Przemyślenia na temat projektu i wykonania:

Wykonanie tego projektu było doskonałą okazją do pogłębienia wiedzy o Laravelu i jego ekosystemie.

**Pozytywne aspekty:**

* **Kompleksowość Laravela:** Laravel oferuje bogactwo wbudowanych funkcji (ORM, uwierzytelnianie, kolejki, scheduler, powiadomienia, polityki), które znacznie przyspieszają rozwój i zapewniają wysoką jakość kodu. Nie trzeba "wynajdywać koła na nowo".
* **Zasady SOLID i KISS:** Starałem się aktywnie stosować te zasady. Użycie polityk, obserwatorów i powiadomień jako osobnych klas jest zgodne z SRP. Prostota Blade i kontrolerów zasobów wpisuje się w KISS. To sprawia, że kod jest bardziej modułowy, łatwiejszy do testowania i utrzymania.
* **Modularność:** Dzięki podziałowi na kontrolery, modele, polityki, powiadomienia i obserwatorów, każda część systemu ma jasno określoną odpowiedzialność.
* **Obsługa błędów:** Laravel ma wbudowane mechanizmy do obsługi błędów i wyjątków, co ułatwia zarządzanie nimi i zapewnia lepsze doświadczenie użytkownika.

**Wyzwania i potencjalne ulepszenia:**

* **Konfiguracja środowiska (bez Dockera):** Chociaż zgodnie z wymaganiem nie używałem Dockera, konfiguracja Composer, Node.js i npm, a zwłaszcza Harmonogramu Zadań w Windows dla schedulera i uruchamiania queue workera, jest bardziej manualna i podatna na błędy niż jednokomendowe `docker-compose up`. W prawdziwym projekcie zdecydowanie użyłbym Dockera, jednak ze względu na czas oraz na drobne problemy sprzętowe, postawiłem na szybsze rozwiązanie.
* **Historia edycji - różnicowanie:** Chociaż zaimplementowałem zapisywanie całej historii w JSON, wyświetlanie precyzyjnych różnic między dwoma wersjami zadania na froncie wymagałoby dodatkowej logiki lub biblioteki JS. Obecnie wyświetlam tylko podsumowanie zmian, co jest prostym, ale efektywnym sposobem na podgląd zmian.
* **Front-end (Blade vs AJAX/SPA):** Użycie czystego Blade jest proste i szybkie do wdrożenia. Jednak dla bardziej dynamicznego interfejsu użytkownika (np. bez przeładowania strony przy filtrach, dynamiczne dodawanie elementów), rozważyłbym użycie Livewire lub prostego frameworka JS (np. Alpine.js lub React/Vue dla SPA) wraz z REST API. Wymagania nie narzucały tego, więc zachowałem prostotę Blade z gotowymi fragmentami Breeze.
* **Testy:** W prawdziwym projekcie dodałbym testy jednostkowe i funkcyjne, aby zapewnić stabilność i poprawność działania aplikacji.
* **Wygląd:** Skupiłem się na funkcjonalności. Styling jest podstawowy, oparty na TailwindCSS z Laravel Breeze bez dodatkowego komplikowania, jednak starałem się aby był jak najbardziej przejrzysty i przyjazny dla użytkownika. 

Ogólnie jestem zadowolony z wykonania projektu. Spełnia wszystkie kluczowe wymagania i porywa się na opcjonalne, zachowując przy tym prostotę i czytelność kodu. Jest to solidna baza do dalszego rozwoju. Z chęcią dla rozwinięcia swoich zdolności rozbuduję tą aplikację w przyszłość.