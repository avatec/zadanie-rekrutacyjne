<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Exception\InvalidUsernameException;
use App\Domain\User\ValueObject\Username;
use PHPUnit\Framework\TestCase;

final class UsernameTest extends TestCase
{
    public function testCreatesValidUsername(): void
    {
        $username = Username::fromString('Bret');

        $this->assertSame('Bret', $username->getValue());
    }

    public function testThrowsOnEmptyString(): void
    {
        $this->expectException(InvalidUsernameException::class);

        Username::fromString('');
    }

    public function testThrowsOnWhitespaceOnly(): void
    {
        $this->expectException(InvalidUsernameException::class);

        Username::fromString('   ');
    }

    public function testThrowsOnTooLongUsername(): void
    {
        $this->expectException(InvalidUsernameException::class);

        Username::fromString(str_repeat('a', 256));
    }

    public function testAcceptsMaxLengthUsername(): void
    {
        $username = Username::fromString(str_repeat('a', 255));

        $this->assertSame(255, mb_strlen($username->getValue()));
    }

    public function testAcceptsMultibyteCharacters(): void
    {
        $username = Username::fromString('użytkownik');

        $this->assertSame('użytkownik', $username->getValue());
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $a = Username::fromString('Bret');
        $b = Username::fromString('Bret');

        $this->assertTrue($a->equals($b));
    }

    public function testEqualsReturnsFalseForDifferentValue(): void
    {
        $a = Username::fromString('Bret');
        $b = Username::fromString('Antonette');

        $this->assertFalse($a->equals($b));
    }
}
