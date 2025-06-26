<?php

/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(
            100,
            'tag',
            function (int $i) {
                $tag = new Tag();
                $tag->setTitle(substr($this->faker->sentence(3), 0, 64));
                $tag->setSlug($this->slugify($tag->getTitle()));
                $tag->setCreatedAt(
                    \DateTimeImmutable::createFromMutable(
                        $this->faker->dateTimeBetween('-100 days', '-1 days')
                    )
                );

                $tag->setUpdatedAt(
                    \DateTimeImmutable::createFromMutable(
                        $this->faker->dateTimeBetween('-100 days', '-1 days')
                    )
                );

                return $tag;
            }
        );
    }

    /**
     * Returns an array of dependencies required by this class.
     *
     * @return array an array of dependency class names
     */
    public function getDependencies(): array
    {
        return [];
    }

    /**
     * Converts a given string into a URL-friendly "slug".
     *
     * @param string $string the input string to be slugified
     *
     * @return string the slugified version of the input string
     */
    private function slugify(string $string): string
    {
        return strtolower(trim((string) preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
