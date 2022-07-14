<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MovieControllerTest extends WebTestCase
{
    const MOVIE = [
        "name" => "The Titanic",
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

    private function addMovie(array $movie = self::MOVIE): void
    {
        $this->client
            ->request(
                Request::METHOD_POST,
                '/api/v1/movies', [], [], [],
                json_encode($movie)
            );
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
                        "1",
                        "2",
                    ],
                    "release_date" => "18-01-1999",
                    "director" => "1",
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

        $id = 'The%20Titanic';
        $this->client
            ->request(Request::METHOD_GET, '/api/v1/movies/' . $id);
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
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var ObjectManager $dm */
        $dm = static::getContainer()->get(ObjectManager::class);
        /** @var User $testUser */
        $testUser = $userRepository->findOneByEmail(AppFixtures::TEST_EMAIL_COM);
        foreach ($testUser->getMovieOwner()->getMovies() as $movie) {
            $dm->remove($movie);
        }
        $dm->flush();
        $this->client->loginUser($testUser);
    }
}
