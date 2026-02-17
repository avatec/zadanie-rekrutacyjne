#!/bin/bash

# Docker wrapper script for Symfony console commands

CONTAINER_NAME="symfony_php"

# Check if container is running
if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
    echo "Error: Container '${CONTAINER_NAME}' is not running."
    echo "Please start Docker containers first: docker-compose up -d"
    exit 1
fi

# Handle different commands
case "$1" in
    console)
        shift
        docker exec "${CONTAINER_NAME}" php bin/console "$@"
        ;;
    composer)
        shift
        docker exec "${CONTAINER_NAME}" composer "$@"
        ;;
    bash|shell)
        docker exec -it "${CONTAINER_NAME}" bash
        ;;
    *)
        echo "Usage: $0 {console|bash|shell} [arguments]"
        echo ""
        echo "Examples:"
        echo "  $0 console cache:clear"
        echo "  $0 console app:sync-users"
        echo "  $0 bash"
        exit 1
        ;;
esac
