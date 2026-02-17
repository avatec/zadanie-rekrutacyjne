<?php

declare(strict_types=1);

namespace App\Tests\Application\Task\StatusTransition;

use App\Application\Task\StatusTransition\AdminStatusTransitionStrategy;
use App\Domain\Task\ValueObject\TaskStatus;
use PHPUnit\Framework\TestCase;

final class AdminStatusTransitionStrategyTest extends TestCase
{
    private AdminStatusTransitionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AdminStatusTransitionStrategy();
    }

    public function testAllowsTodoToInProgress(): void
    {
        $this->assertTrue(
            $this->strategy->canTransition(TaskStatus::TODO, TaskStatus::IN_PROGRESS)
        );
    }

    public function testAllowsInProgressToDone(): void
    {
        $this->assertTrue(
            $this->strategy->canTransition(TaskStatus::IN_PROGRESS, TaskStatus::DONE)
        );
    }

    public function testAllowsInProgressToTodo(): void
    {
        $this->assertTrue(
            $this->strategy->canTransition(TaskStatus::IN_PROGRESS, TaskStatus::TODO)
        );
    }

    public function testAllowsDoneToTodo(): void
    {
        $this->assertTrue(
            $this->strategy->canTransition(TaskStatus::DONE, TaskStatus::TODO)
        );
    }

    public function testBlocksTodoToDone(): void
    {
        $this->assertFalse(
            $this->strategy->canTransition(TaskStatus::TODO, TaskStatus::DONE)
        );
    }

    public function testBlocksDoneToInProgress(): void
    {
        $this->assertFalse(
            $this->strategy->canTransition(TaskStatus::DONE, TaskStatus::IN_PROGRESS)
        );
    }

    public function testBlocksSameStatusTransition(): void
    {
        $this->assertFalse(
            $this->strategy->canTransition(TaskStatus::TODO, TaskStatus::TODO)
        );
        $this->assertFalse(
            $this->strategy->canTransition(TaskStatus::IN_PROGRESS, TaskStatus::IN_PROGRESS)
        );
        $this->assertFalse(
            $this->strategy->canTransition(TaskStatus::DONE, TaskStatus::DONE)
        );
    }
}
