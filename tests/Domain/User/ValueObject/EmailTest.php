<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCreatesValidEmail(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertSame('user@example.com', $email->getValue());
    }

    public function testThrowsOnInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('not-an-email');
    }

    public function testThrowsOnEmptyString(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('');
    }

    public function testThrowsOnMissingDomain(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('user@');
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $a = Email::fromString('user@example.com');
        $b = Email::fromString('user@example.com');

        $this->assertTrue($a->equals($b));
    }

    public function testEqualsReturnsFalseForDifferentValue(): void
    {
        $a = Email::fromString('user@example.com');
        $b = Email::fromString('other@example.com');

        $this->assertFalse($a->equals($b));
    }
}
