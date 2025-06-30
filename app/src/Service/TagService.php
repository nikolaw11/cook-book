<?php

/*
 * This file is part of the YourProject package.
 *
 * (c) Your Name <your.email@example.com>
 *
 * For license information, please view the LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    /**
     * Constructor.
     *
     * @param TagRepository          $tagRepository Tag repository
     * @param PaginatorInterface     $paginator     Paginator
     * @param EntityManagerInterface $entityManager Entity manager
     */
    public function __construct(private readonly TagRepository $tagRepository, private readonly PaginatorInterface $paginator, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination Interface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        $queryBuilder = $this->tagRepository->createQueryBuilder('t');

        return $this->paginator->paginate(
            $queryBuilder,
            $page
        );
    }

    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete action.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }
}
