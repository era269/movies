<?php

declare(strict_types=1);

namespace App\Tests\Domain\MovieOwnersDecorator;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\MovieAddedEvent;
use App\Domain\MovieLibraryInterface;
use App\Domain\MovieOwnerId;
use App\Domain\MovieOwnerInterface;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Domain\MovieLibraryDecorator\MovieLibraryAddExistingMovieDecorator;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MovieOwnersAddExistingMovieDecoratorTest extends TestCase
{
    /**
     * @var MovieOwnerInterface|MockObject
     */
    private MovieOwnerInterface $movieOwner;
    private AddMovieCommand $command;

    /**
     * @dataProvider dataProvider
     */
    public function testAddMovie(bool $hasMovie, string $eventClassName)
    {
        $this->movieOwner
            ->method('hasMovie')
            ->willReturn($hasMovie);

        $eventActual = $this->decorator->addMovie(
            $this->command
        );

        self::assertEquals($eventClassName, get_class($eventActual));
    }

    public function dataProvider(): array
    {
        return [
            ['has-movie' => false, 'event' => MovieAddedEvent::class],
            ['has-movie' => true, 'event' => FailedToAddMovieEvent::class],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new AddMovieCommand(
            new MovieOwnerId(1),
            'some-name',
            [],
            new DateTime(),
            'some-director',
            []
        );

        /** @var MovieLibraryInterface&MockObject $decorated */
        $decorated = self::createMock(MovieLibraryInterface::class);
        $decorated
            ->method('addMovie')
            ->willReturn(
                MovieAddedEvent::fromCommand($this->command)
            );
        /** @var MovieOwnerInterface&MockObject $movieOwner */
        $this->movieOwner = self::createMock(MovieOwnerInterface::class);
        /** @var MovieOwnerRepositoryInterface&MockObject $repository */
        $repository = self::createMock(MovieOwnerRepositoryInterface::class);
        $repository
            ->method('getMovieOwner')
            ->willReturn($this->movieOwner);
        $this->decorator = new MovieLibraryAddExistingMovieDecorator(
            $decorated,
            $repository
        );
    }
}
