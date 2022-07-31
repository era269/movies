<?php

namespace App\Controller;

use App\Domain\Message\AddMovieCommand;
use App\Domain\Message\FailedToAddMovieEvent;
use App\Domain\Message\GetMovieByNameQuery;
use App\Domain\Message\GetMoviesQuery;
use App\Domain\Message\MovieAddedEvent;
use App\Domain\MovieOwnerId;
use App\Domain\MovieLibraryInterface;
use App\Dto\MovieDto;
use App\Entity\User;
use OutOfBoundsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/movies")
 */
class MovieController extends AbstractController
{
    private const EVENT_HTTP_CODE_MAP = [
        MovieAddedEvent::class => 201,
        FailedToAddMovieEvent::class => 409,
    ];

    public function __construct(private readonly string $dateFormat, private readonly MovieLibraryInterface $movieOwners)
    {
    }

    /**
     * @Route(methods={"POST"})
     */
    public function add(Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        /** @var MovieDto $addMovieDto */
        $addMovieDto = $serializer->deserialize($request->getContent(), MovieDto::class, JsonEncoder::FORMAT);
        $violationList = $validator->validate($addMovieDto);
        if ($violationList->count()) {
            throw new BadRequestHttpException($violationList);
        }

        $movieMessage = $this->movieOwners->addMovie(
            AddMovieCommand::fromDto(
                $this->getMovieOwnerId(),
                $addMovieDto,
                $this->dateFormat
            )
        );

        return $this->json(
            $movieMessage,
            self::EVENT_HTTP_CODE_MAP[$movieMessage::class]
        );
    }

    private function getMovieOwnerId(): MovieOwnerId
    {
        /** @var User $user */
        $user = $this->getUser();

        return $user->getMovieOwnerId();
    }

    /**
     * @Route("/{name}", methods={"GET"})
     */
    public function getOne(string $name): JsonResponse
    {
        $query = new GetMovieByNameQuery(
            $this->getMovieOwnerId(),
            $name
        );

        try {
            $movie = $this->movieOwners
                ->getMovie($query);
        } catch (OutOfBoundsException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        return $this->json($movie);
    }

    /**
     * @Route(methods={"GET"})
     */
    public function all(): JsonResponse
    {
        return $this->json(
            $this->movieOwners->getMovies(
                new GetMoviesQuery(
                    $this->getMovieOwnerId()
                )
            )
        );
    }
}
