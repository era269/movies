<?php

namespace App\Serializer\Normalizer;

use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MovieNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private string $dateFormat;

    public function __construct(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param Movie $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $movieRatings = [];
        foreach ($object->getRatings() as $movieRating) {
            $movieRatings[$movieRating->getType()->getName()] = $movieRating->getValue();
        }

        return [
            "name" => $object->getName(),
            "casts" => array_map(
                fn (Actor $a) => $a->getName(),
                $object->getCasts()->toArray()
            ),
            "release_date" => $object->getReleaseDate()->format($this->dateFormat),
            "director" => $object->getDirector()->getName(),
            "ratings" => $movieRatings,
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Movie;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
