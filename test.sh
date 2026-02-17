#!/bin/bash

BASE_URL="http://localhost:8090"
PASS=0
FAIL=0
COOKIE_JAR=$(mktemp)

run_test() {
    local name="$1"
    local query="$2"
    local response

    response=$(curl -s -X POST "$BASE_URL/" \
        -H "Content-Type: application/json" \
        -b "$COOKIE_JAR" -c "$COOKIE_JAR" \
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
        -b "$COOKIE_JAR" -c "$COOKIE_JAR" \
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

run_test "me" \
    "{ me { id username name email } }"

echo ""
echo "--- Tasks ---"

CREATE_RESPONSE=$(curl -s -X POST "$BASE_URL/" \
    -H "Content-Type: application/json" \
    -b "$COOKIE_JAR" -c "$COOKIE_JAR" \
    -d '{"query":"mutation { createTask(userId: 1, title: \"Test task\", description: \"Opis testowy\") { id title status } }"}')

TASK_ID=$(echo "$CREATE_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | grep -o '[0-9]*')

if echo "$CREATE_RESPONSE" | grep -q '"errors"'; then
    echo "❌ FAIL: createTask(userId: 1)"
    echo "   Response: $CREATE_RESPONSE"
    FAIL=$((FAIL + 1))
else
    echo "✅ PASS: createTask(userId: 1) [taskId=$TASK_ID]"
    echo "   Response: $CREATE_RESPONSE"
    PASS=$((PASS + 1))
fi

run_test "tasks" \
    "{ tasks { id title status } }"

run_test "userTasks(userId: 1)" \
    "{ userTasks(userId: 1) { id title status } }"

run_test "updateTaskStatus -> IN_PROGRESS" \
    "mutation { updateTaskStatus(taskId: $TASK_ID, status: \\\"IN_PROGRESS\\\") { id status } }"

run_test "updateTaskStatus -> DONE" \
    "mutation { updateTaskStatus(taskId: $TASK_ID, status: \\\"DONE\\\") { id status } }"

echo ""
echo "--- Non-admin revert attempt (login as Antonette - no admin rights) ---"

run_test "login as non-admin (Antonette)" \
    "mutation { login(username: \\\"Antonette\\\") { id username name email } }"

run_test_expect_error "updateTaskStatus -> TODO (non-admin cannot revert from DONE)" \
    "mutation { updateTaskStatus(taskId: $TASK_ID, status: \\\"TODO\\\") { id status } }" \
    "Cannot transition"

echo ""
echo "--- Admin revert (re-login as admin user: Bret with is_admin=true) ---"

run_test "login as admin (Bret)" \
    "mutation { login(username: \\\"Bret\\\") { id username name email } }"

run_test "updateTaskStatus -> TODO (admin revert via session)" \
    "mutation { updateTaskStatus(taskId: $TASK_ID, status: \\\"TODO\\\") { id status } }"

run_test "task history" \
    "{ tasks { id title history { type occurredAt data } } }"

echo ""
echo "========================================"
echo " Results: $PASS passed, $FAIL failed"
echo "========================================"

rm -f "$COOKIE_JAR"
