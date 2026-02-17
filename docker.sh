#!/bin/bash

# Docker wrapper script for Symfony console commands

CONTAINER_NAME="symfony_php"
SERVICE_NAME="php"

# Check if container is running using docker compose
if ! docker compose ps | grep -q "${CONTAINER_NAME}.*Up"; then
    echo "Error: Container '${CONTAINER_NAME}' is not running."
    echo "Please start Docker containers first: docker compose up -d"
    exit 1
fi

# Handle different commands
case "$1" in
    console)
        shift
        docker compose exec "${SERVICE_NAME}" php bin/console "$@"
        ;;
    composer)
        shift
        docker compose exec "${SERVICE_NAME}" composer "$@"
        ;;
    unittest)
        shift
        docker compose exec "${SERVICE_NAME}" ./vendor/bin/phpunit "$@"
        ;;
    sa|static-analysis)
        echo "=== Running Static Analysis Tools ==="
        echo ""
        echo ">>> PHP-CS-Fixer (PSR-12 compliance check)..."
        docker compose exec "${SERVICE_NAME}" ./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
        CS_FIXER_EXIT=$?
        echo ""
        echo ">>> PHPStan (Static Analysis with strict rules)..."
        docker compose exec "${SERVICE_NAME}" ./vendor/bin/phpstan analyse
        PHPSTAN_EXIT=$?
        echo ""
        echo "=== Static Analysis Summary ==="
        echo "PHP-CS-Fixer: $([ $CS_FIXER_EXIT -eq 0 ] && echo '✓ PASSED' || echo '✗ FAILED')"
        echo "PHPStan:      $([ $PHPSTAN_EXIT -eq 0 ] && echo '✓ PASSED' || echo '✗ FAILED')"
        echo ""
        [ $CS_FIXER_EXIT -eq 0 ] && [ $PHPSTAN_EXIT -eq 0 ] && exit 0 || exit 1
        ;;
    fix)
        echo ">>> Running PHP-CS-Fixer (auto-fix mode)..."
        docker compose exec "${SERVICE_NAME}" ./vendor/bin/php-cs-fixer fix --verbose
        ;;
    bash|shell)
        docker compose exec "${SERVICE_NAME}" bash
        ;;
    *)
        echo "Usage: $0 {console|composer|unittest|sa|fix|bash|shell} [arguments]"
        echo ""
        echo "Examples:"
        echo "  $0 console cache:clear"
        echo "  $0 console app:sync-users"
        echo "  $0 composer install"
        echo "  $0 unittest"
        echo "  $0 unittest tests/Application/Task/Factory/TaskFactoryTest.php"
        echo "  $0 sa                  # Run all static analysis tools"
        echo "  $0 fix                 # Auto-fix code style issues"
        echo "  $0 bash"
        exit 1
        ;;
esac
