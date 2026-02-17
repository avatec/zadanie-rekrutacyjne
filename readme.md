# Zadanie rekrutacyjne

## Uruchamianie środowiska Docker i aplikacji
Aplikacja domyślnie dostępna jest na localhost:8090 (można zmienić port edytując plik docker-compose.yaml linia 28)
```bash
chmod +x ./docker.sh && chmod +x ./test.sh
docker compose up -d --build
./docker.sh console doctrine:schema:update --force
./docker.sh console doctrine:migrations:migrate   
./docker.sh console app:sync-users
```

Następnie należy wykonać polecenie, aby ustalić usera 1 jako admina:
```bash
./docker.sh console dbal:run-sql "UPDATE users SET is_admin = true WHERE id = 1"
```

## Uruchomienie testów GraphQL
```bash
./test.sh
```

## Dostępne polecenia (docker.sh)

### Symfony Console
Uruchamianie komend Symfony:
```bash
./docker.sh console [komenda]
```

Przykłady:
```bash
./docker.sh console cache:clear
./docker.sh console app:sync-users
```

### Composer
Zarządzanie zależnościami:
```bash
./docker.sh composer [komenda]
```

Przykład:
```bash
./docker.sh composer install
```

### Testy jednostkowe
Uruchamianie testów PHPUnit:
```bash
./docker.sh unittest [opcjonalna ścieżka do testu]
```

Przykłady:
```bash
./docker.sh unittest
./docker.sh unittest tests/Application/Task/Factory/TaskFactoryTest.php
```

### Analiza statyczna
Uruchamianie PHP-CS-Fixer i PHPStan:
```bash
./docker.sh sa
```
lub
```bash
./docker.sh static-analysis
```

### Automatyczne poprawianie kodu
Automatyczne naprawianie problemów ze stylem kodu:
```bash
./docker.sh fix
```

### Shell kontenera
Dostęp do bash w kontenerze:
```bash
./docker.sh bash
```
lub
```bash
./docker.sh shell
```

## Synchronizacja użytkowników
```bash
./docker.sh console app:sync-users
```

## Test dla GraphQL:
```bash
curl -X POST http://localhost:8080/ \
-H "Content-Type: application/json" \
-d '{"query":"{ me { id username name email } }"}'
```