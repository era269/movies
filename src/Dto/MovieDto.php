<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class MovieDto
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $name;
    /**
     * @Assert\Type("array")
     * @Assert\Count(min = 1)
     * @Assert\NotBlank()
     * @Assert\All({
     *     @Assert\Type("string"),
     *     @Assert\Length(min=1),
     *     @Assert\NotBlank()
     * })
     */
    public $casts;
    /**
     * @Assert\DateTime(format = "d-m-Y")
     * @Assert\NotBlank()
     * @SerializedName("release_date")
     */
    public $releaseDate;
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $director;
    /**
     * @Assert\Collection(
     *     fields = {
     *         "imdb" = {@Assert\Type("float"), @Assert\PositiveOrZero(), @Assert\LessThanOrEqual(10)},
     *         "rotten_tomatto" = {@Assert\Type("float"), @Assert\PositiveOrZero(), @Assert\LessThanOrEqual(10)}
     *     }
     * )
     * @Assert\NotBlank()
     */
    public $ratings;
}
