# Zadanie rekrutacyjne

## Uruchamianie komend
```bash
./docker.sh console [komenta]
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