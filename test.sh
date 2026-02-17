#!/bin/bash

BASE_URL="http://localhost:8090"
PASS=0
FAIL=0

run_test() {
    local name="$1"
    local query="$2"
    local response

    response=$(curl -s -X POST "$BASE_URL/" \
        -H "Content-Type: application/json" \
        -d "{\"query\":\"$query\"}")

    if echo "$response" | grep -q '"errors"'; then
        echo "❌ FAIL: $name"
        echo "   Response: $response"
        FAIL=$((FAIL + 1))
    else
        echo "✅ PASS: $name"
        echo "   Response: $response"
        PASS=$((PASS + 1))
    fi
}

run_test_expect_error() {
    local name="$1"
    local query="$2"
    local expected_msg="$3"
    local response

    response=$(curl -s -X POST "$BASE_URL/" \
        -H "Content-Type: application/json" \
        -d "{\"query\":\"$query\"}")

    if echo "$response" | grep -q '"errors"'; then
        if [ -n "$expected_msg" ] && ! echo "$response" | grep -q "$expected_msg"; then
            echo "❌ FAIL: $name (unexpected error message)"
            echo "   Response: $response"
            FAIL=$((FAIL + 1))
        else
            echo "✅ PASS: $name (expected error)"
            echo "   Response: $response"
            PASS=$((PASS + 1))
        fi
    else
        echo "❌ FAIL: $name (expected error but got success)"
        echo "   Response: $response"
        FAIL=$((FAIL + 1))
    fi
}

echo "========================================"
echo " GraphQL API Tests — port 8090"
echo "========================================"
echo ""

echo "--- Users ---"

run_test "login(username: Bret)" \
    "mutation { login(username: \\\"Bret\\\") { id username name email } }"

run_test "me(userId: 1)" \
    "{ me(userId: 1) { id username name email } }"

echo ""
echo "--- Tasks ---"

run_test "createTask(userId: 1)" \
    "mutation { createTask(userId: 1, title: \\\"Test task\\\", description: \\\"Opis testowy\\\") { id title status } }"

run_test "tasks" \
    "{ tasks { id title status } }"

run_test "userTasks(userId: 1)" \
    "{ userTasks(userId: 1) { id title status } }"

run_test "updateTaskStatus -> IN_PROGRESS" \
    "mutation { updateTaskStatus(taskId: 1, status: \\\"IN_PROGRESS\\\") { id status } }"

run_test "updateTaskStatus -> DONE" \
    "mutation { updateTaskStatus(taskId: 1, status: \\\"DONE\\\") { id status } }"

run_test "updateTaskStatus -> TODO (admin revert)" \
    "mutation { updateTaskStatus(taskId: 1, status: \\\"TODO\\\", isAdmin: true) { id status } }"

run_test_expect_error "updateTaskStatus -> DONE (invalid: TODO->DONE skip IN_PROGRESS)" \
    "mutation { updateTaskStatus(taskId: 1, status: \\\"DONE\\\") { id status } }" \
    "Cannot transition"

run_test "task history" \
    "{ tasks { id title history { type occurredAt data } } }"

echo ""
echo "========================================"
echo " Results: $PASS passed, $FAIL failed"
echo "========================================"
