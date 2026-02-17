<?php

declare(strict_types=1);

namespace App\Tests\Application\Task\Factory;

use App\Application\Task\Factory\TaskFactory;
use App\Domain\Task\Event\TaskCreatedEvent;
use App\Domain\Task\Task;
use PHPUnit\Framework\TestCase;

final class TaskFactoryTest extends TestCase
{
    private TaskFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new TaskFactory();
    }

    public function testCreateTaskReturnsTaskInstance(): void
    {
        $task = $this->factory->create(
            taskId: 1,
            userId: 100,
            title: 'Test Task',
            description: 'Test Description'
        );

        $this->assertInstanceOf(Task::class, $task);
    }

    public function testCreateTaskWithValidDataProducesCorrectTask(): void
    {
        $taskId = 1;
        $userId = 100;
        $title = 'Test Task';
        $description = 'Test Description';

        $task = $this->factory->create($taskId, $userId, $title, $description);

        $this->assertSame($taskId, $task->getTaskId()->getValue());
        $this->assertSame($userId, $task->getUserId()->getValue());
        $this->assertSame($title, $task->getTitle()->getValue());
        $this->assertSame($description, $task->getDescription());
    }

    public function testCreateTaskGeneratesTaskCreatedEvent(): void
    {
        $task = $this->factory->create(
            taskId: 1,
            userId: 100,
            title: 'Test Task',
            description: 'Test Description'
        );

        $events = $task->getUncommittedEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(TaskCreatedEvent::class, $events[0]);
    }

    public function testCreateTaskWithEmptyTitleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Task title cannot be empty');

        $this->factory->create(
            taskId: 1,
            userId: 100,
            title: '',
            description: 'Test Description'
        );
    }

    public function testCreateTaskWithWhitespaceOnlyTitleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Task title cannot be empty');

        $this->factory->create(
            taskId: 1,
            userId: 100,
            title: '   ',
            description: 'Test Description'
        );
    }

    public function testCreateTaskWithTooLongTitleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Task title cannot be longer than 255 characters');

        $longTitle = str_repeat('a', 256);

        $this->factory->create(
            taskId: 1,
            userId: 100,
            title: $longTitle,
            description: 'Test Description'
        );
    }

    public function testCreateTaskTrimsTitle(): void
    {
        $task = $this->factory->create(
            taskId: 1,
            userId: 100,
            title: '  Test Task  ',
            description: 'Test Description'
        );

        $this->assertSame('Test Task', $task->getTitle()->getValue());
    }

    public function testCreateTaskSetsDefaultTodoStatus(): void
    {
        $task = $this->factory->create(
            taskId: 1,
            userId: 100,
            title: 'Test Task',
            description: 'Test Description'
        );

        $this->assertSame('TODO', $task->getStatus()->value);
    }

    public function testCreateTaskWithDifferentUserIds(): void
    {
        $task1 = $this->factory->create(1, 100, 'Task 1', 'Description 1');
        $task2 = $this->factory->create(2, 200, 'Task 2', 'Description 2');

        $this->assertSame(100, $task1->getUserId()->getValue());
        $this->assertSame(200, $task2->getUserId()->getValue());
    }

    public function testCreateTaskWithEmptyDescription(): void
    {
        $task = $this->factory->create(
            taskId: 1,
            userId: 100,
            title: 'Test Task',
            description: ''
        );

        $this->assertSame('', $task->getDescription());
    }

    public function testTaskCreatedEventContainsCorrectData(): void
    {
        $taskId = 1;
        $userId = 100;
        $title = 'Test Task';
        $description = 'Test Description';

        $task = $this->factory->create($taskId, $userId, $title, $description);
        $events = $task->getUncommittedEvents();

        /** @var TaskCreatedEvent $event */
        $event = $events[0];

        $this->assertSame($taskId, $event->getTaskId());
        $this->assertSame($userId, $event->getUserId());
        $this->assertSame($title, $event->getTitle());
        $this->assertSame($description, $event->getDescription());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredAt());
    }
}
