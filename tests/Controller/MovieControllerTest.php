<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Domain\Message\MovieAddedEvent;
use App\Domain\MovieOwnerRepositoryInterface;
use App\Entity\MovieOwner;
use App\Entity\User;
use App\Listener\MovieOwnerPersistEventListener;
use App\Listener\UserMovieNotificationEventListener;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;

class MovieControllerTest extends WebTestCase
{
    private const MOVIE_NAME = 'The Titanic';
    private const MOVIE      = [
        "name" => self::MOVIE_NAME,
        "casts" => [
            "DiCaprio",
            "Kate Winslet",
        ],
        "release_date" => "18-01-1998",
        "director" => "James Cameron",
        "ratings" => [
            "imdb" => 7.8,
            "rotten_tomatto" => 8.2,
        ],
    ];
    private KernelBrowser $client;

    /**
     * @dataProvider addDataProvider
     */
    public function testAdd(array $movie)
    {
        $this->addMovie($movie);

        $this->assertResponseIsSuccessful();

        self::assertEquals(
            Response::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode()
        );

        self::assertTrue($this->eventConsumed(
            MovieAddedEvent::class,
            MovieOwnerPersistEventListener::class
        ));

        self::assertTrue($this->eventConsumed(
            MovieAddedEvent::class,
            UserMovieNotificationEventListener::class
        ));

        self::assertTrue($this->eventConsumed(
            MessageEvent::class,
            EnvelopeListener::class
        ));
    }

    private function addMovie(array $movie = self::MOVIE): void
    {
        $this->client
            ->request(
                Request::METHOD_POST,
                '/api/v1/movies', [], [], [],
                json_encode($movie)
            );
    }

    /**
     * @param class-string $event
     * @param class-string $eventListener
     */
    private function eventConsumed(string $event, string $eventListener): bool
    {
        /** @var TraceableEventDispatcher $eventDispatcher */
        $eventDispatcher = static::getContainer()->get(EventDispatcherInterface::class);
        $calledListeners = $eventDispatcher->getCalledListeners($this->client->getRequest());
        foreach ($calledListeners as $data) {
            if ($data['event'] === $event && strpos($data['pretty'], $eventListener) === 0) {
                return true;
            }
        }
        return false;
    }

    public function testAddMovieWithTheSameName(): void
    {
        $this->addMovie();

        self::assertTrue($this->eventConsumed(
            MovieAddedEvent::class,
            MovieOwnerPersistEventListener::class
        ));

        self::assertTrue($this->eventConsumed(
            MovieAddedEvent::class,
            UserMovieNotificationEventListener::class
        ));

        self::assertTrue($this->eventConsumed(
            MessageEvent::class,
            EnvelopeListener::class
        ));

        $this->assertResponseIsSuccessful();

        $this->addMovie();

        self::assertEquals(
            Response::HTTP_CONFLICT,
            $this->client->getResponse()->getStatusCode()
        );

        self::assertFalse($this->eventConsumed(
            MovieAddedEvent::class,
            MovieOwnerPersistEventListener::class
        ));

        self::assertFalse($this->eventConsumed(
            MovieAddedEvent::class,
            UserMovieNotificationEventListener::class
        ));

        self::assertFalse($this->eventConsumed(
            MessageEvent::class,
            EnvelopeListener::class
        ));
    }

    /**
     * @dataProvider badRequestDataProvider
     */
    public function testAddBadRequest(array $movie)
    {
        $this->addMovie($movie);

        self::assertEquals(
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testNotFound()
    {
        $this->addMovie();

        $this->client
            ->request(Request::METHOD_GET, '/api/v1/movies/' . 'invalid-movie-name');
        self::assertEquals(
            Response::HTTP_NOT_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testAccessToNotOwnMovie()
    {
        $this->addMovie();

        $this->client->loginUser(
            $this->getUser(AppFixtures::TEST_EMAIL_SECOND)
        );
        $this->client
            ->request(Request::METHOD_GET, '/api/v1/movies/' . self::MOVIE_NAME);
        self::assertEquals(
            Response::HTTP_NOT_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }

    private function getUser(string $email = AppFixtures::TEST_EMAIL): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var User $testUser */
        $testUser = $userRepository->findOneByEmail($email);

        return $testUser;
    }

    public function addDataProvider(): array
    {
        return [
            [
                self::MOVIE,
            ],
            [
                [
                    "name" => "1",
                    "casts" => [
                        "DiCaprio",
                        "2",
                        "3",
                    ],
                    "release_date" => "18-01-1999",
                    "director" => "James Cameron",
                    "ratings" => [
                        "imdb" => 1.1,
                        "rotten_tomatto" => 1.1,
                    ],
                ],
            ],
        ];
    }

    public function badRequestDataProvider(): array
    {
        return [
            'name-not-present' => self::fieldNotPresent('name'),
            'name-empty-array' => self::fieldIs('name', []),
            'name-array' => self::fieldIs('name', [null]),
            'name-empty' => self::fieldIs('name', ''),
            'name-null' => self::fieldIsNull('name'),
            'name-int' => self::fieldIsInt('name'),
            'director-not-present' => self::fieldNotPresent('director'),
            'director-empty-array' => self::fieldIs('director', []),
            'director-array' => self::fieldIs('director', [null]),
            'director-empty' => self::fieldIs('director', ''),
            'director-null' => self::fieldIsNull('director'),
            'director-int' => self::fieldIsInt('director'),
            'casts-not-present' => self::fieldNotPresent('casts'),
            'casts-empty-array' => self::fieldIs('casts', [[]]),
            'casts-array' => self::fieldIs('casts', [[null]]),
            'casts-empty' => self::fieldIs('casts', []),
            'casts-null' => self::fieldIs('casts', [null]),
            'casts-int' => self::fieldIs('casts', [1]),
            'release_date-not-present' => self::fieldNotPresent('release_date'),
            'release_date-wrong-format-1' => self::fieldIs('release_date', '2022-12-30'),
            'release_date-wrong-format-2' => self::fieldIs('release_date', '2022-30-12'),
            'release_date-wrong-format-3' => self::fieldIs('release_date', '12:30:2022'),
            'release_date-int' => self::fieldIsInt('release_date'),
            'ratings-not-present' => self::fieldNotPresent('ratings'),
            'ratings-partial-1' => self::fieldIs('ratings', ['imdb' => 1.1]),
            'ratings-partial-2' => self::fieldIs('ratings', ['rotten_tomatto' => 1.1]),
            'ratings-invalid' => self::fieldIs('ratings', ['imdb' => 1.1, 'rotten_tomatto' => 1.1, 'ff' => 1.1]),
            'ratings-string' => self::fieldIs('ratings', ['imdb' => 1.1, 'rotten_tomatto' => '1.1']),
            'ratings-negative' => self::fieldIs('ratings', ['imdb' => 1.1, 'rotten_tomatto' => -1.1]),
            'ratings-too-big' => self::fieldIs('ratings', ['imdb' => 1.1, 'rotten_tomatto' => 99.1]),
        ];
    }

    static private function fieldNotPresent(string $field): array
    {
        return [array_diff_key(self::MOVIE, array_flip([$field]))];
    }

    static private function fieldIs(string $field, $value): array
    {
        return [[$field => $value] + self::MOVIE];
    }

    static private function fieldIsNull(string $field): array
    {
        return self::fieldIs($field, null);
    }

    static private function fieldIsInt(string $field): array
    {
        return self::fieldIs($field, 123);
    }

    public function testGetOne()
    {
        $this->addMovie();

        $this->client
            ->request(Request::METHOD_GET, '/api/v1/movies/' . self::MOVIE_NAME);
        self::assertEquals(
            json_encode(self::MOVIE),
            $this->client->getResponse()->getContent()
        );
    }

    public function testAll()
    {
        $this->addMovie();
        $this->assertResponseIsSuccessful();
        $this->client
            ->request(Request::METHOD_GET, '/api/v1/movies');
        self::assertEquals(
            json_encode([self::MOVIE]),
            $this->client->getResponse()->getContent()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->loginUser($this->getUser());
        $this->cleanMovies();
    }

    private function cleanMovies(): void
    {
        /** @var ObjectManager $dm */
        $dm = static::getContainer()->get(ObjectManager::class);
        foreach ($this->getMovieOwner()->getMovies() as $movie) {
            $dm->remove($movie);
        }
        $dm->flush();
    }

    private function getMovieOwner(string $email = AppFixtures::TEST_EMAIL): MovieOwner
    {
        /** @var MovieOwnerRepositoryInterface $ownerRepository */
        $ownerRepository = static::getContainer()->get(MovieOwnerRepositoryInterface::class);

        return $ownerRepository->getMovieOwner(
            $this->getUser($email)->getMovieOwnerId()
        );
    }
}
