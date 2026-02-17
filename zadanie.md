# TaskManager

## Opis zadania

Twoim zadaniem jest stworzenie aplikacji do zarządzania zadaniami, w której użytkownicy mogą tworzyć,
przypisywać i śledzić status swoich zadań.

Aplikacja powinna posiadać GraphQL API/REST, być oparta na zasadach Domain-Driven Design (DDD)
oraz wykorzystywać Event Sourcing do śledzenia zmian w zadaniach.

Dodatkowo, aplikacja powinna integrować się z zewnętrznym API, np. JSONPlaceholder -
https://jsonplaceholder.typicode.com/guide/, aby pobierać przykładowe dane użytkowników.

### Technologie
1. Symfony 7+
2. GraphQL (OverblogGraphQLBundle)
3. Doctrine ORM
4. Event Sourcing (Symfony Messenger)
5. Integracja z JSONPlaceholder (lub innym Fake API)
6. Testy jednostkowe (PHPUnit)
7. Docker

### Funkcjonalności

Podział kodu wg zasad Domain-Driven Design:

- src/Domain - warstwa domenowa
- src/Application - warstwa aplikacyjna
- src/Infrastructure - warstwa infrastruktury

#### Agregat 1: User

1. Pobranie użytkowników z API JSONPlaceholder (integracja)
2. Logowanie użytkownika
3. Pobieranie danych o zalogowanym użytkowniku

#### Agregat 2: Task

1. Tworzenie zadania (nazwa, opis, status, użytkownik przypisany)
2. Zmiana statusu zadania (T o Do, In Progress, Done)
3. Lista zadań przypisanych do użytkownika
4. Lista wszystkich zadań (dla admina)
5. Historia zmian zadania (Event Sourcing)

### Integracja z API

W aplikacji należy dodać endpoint GraphQL/REST, który pozwoli pobrać z systemu listę zadań.
Dodatkowo należy wykonać integrację REST API z JSONPlaceholder, w celu pobrania dostępnych w
systemie użytkowników i zapisze ich w bazie: https://jsonplaceholder.typicode.com/users.

#### Event Sourcing

Każda operacja na zadaniu (utworzenie, zmiana statusu) generuje zdarzenie, które jest zapisywane w
Event Store.

Przykładowe zdarzenia:

- TaskCreatedEvent – zadanie zostało utworzone
- TaskStatusUpdatedEvent – zmiana statusu zadania

#### Wzorce projektowe

W ramach zadania wykorzystaj poniższe wzorce projektowe wykorzystywane w PHP:

- Factory Pattern – ten wzorzec umożliwia tworzenie obiektów bez bezpośredniego używania operatora new, co zwiększa elastyczność kodu i ułatwia jego rozszerzanie.
- Strategy Pattern – dzięki zastosowaniu strategii kod staje się bardziej modularny i zgodny z zasadą  otwarte-zamknięte, co pozwala na łatwe dodawanie nowych sposobów realizacji danej funkcjonalności bez ingerencji w istniejący kod.

Wykorzystanie obu wzorców pozwoli na bardziej przejrzystą i elastyczną architekturę aplikacji, umożliwiając łatwe rozszerzanie funkcjonalności oraz testowanie poszczególnych komponentów niezależnie od siebie.

### Testy jednostkowe (opcjonalne)

Należy napisać testy dla utworzonej w ramach wzorców projektowych fabryki, aby upewnić się, że działa ona zgodnie z założeniami i poprawnie tworzy obiekty wymaganych klas. T esty powinny obejmować zarówno przypadki standardowe, jak i sytuacje brzegowe, sprawdzając, czy fabryka zwraca poprawne instancje, obsługuje błędne dane wejściowe oraz zachowuje zgodność z oczekiwanym interfejsem.


### Repozytorium kodu

W trakcie implementacji kod należy wrzucić na repozytorium, aby zapewnić jego wersjonowanie. Ważne jest, aby każdy commit był dobrze opisany i jasno wskazywał, jakie zmiany zostały wprowadzone. Należy stosować czytelne i zwięzłe komunikaty. Regularne commitowanie pozwala śledzić postęp prac oraz umożliwia szybki powrót do wcześniejszej wersji kodu w razie potrzeby. Do konwencji nazywania commitów można wykorzystać: https://www.conventionalcommits.org/en/v1.0.0/. 