<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Exception\InvalidUserIdException;
use App\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class UserIdTest extends TestCase
{
    public function testCreatesValidUserId(): void
    {
        $id = UserId::fromInt(1);

        $this->assertSame(1, $id->getValue());
    }

    public function testThrowsOnZero(): void
    {
        $this->expectException(InvalidUserIdException::class);

        UserId::fromInt(0);
    }

    public function testThrowsOnNegative(): void
    {
        $this->expectException(InvalidUserIdException::class);

        UserId::fromInt(-1);
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $a = UserId::fromInt(1);
        $b = UserId::fromInt(1);

        $this->assertTrue($a->equals($b));
    }

    public function testEqualsReturnsFalseForDifferentValue(): void
    {
        $a = UserId::fromInt(1);
        $b = UserId::fromInt(2);

        $this->assertFalse($a->equals($b));
    }
}
