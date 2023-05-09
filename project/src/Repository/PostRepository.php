<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Post\Category;
use App\Entity\Post\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * ManagerRegistry $registry
     * PaginatorInterface $paginatorInterface
     */
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginatorInterface
    ) {
        parent::__construct($registry, Post::class);
    }

    /**
     * Find array list to published Post
     *
     * @param Int $page
     * @param Category $category
     * @param Tag $tag
     *
     * @return PaginationInterface
     */
    public function findPublished(
        Int $page, 
        ?Category $category = null,
        ?Tag $tag = null
    ): PaginationInterface {
        $postList = $this->createQueryBuilder('p')
            ->where('p.state LIKE :state')
            ->setParameter('state', Post::STATE_LIST[1])
            ->orderBy('p.createdAt', 'DESC');

        if (isset($category)) {
            $postList = $postList
                ->join('p.categories', 'c')
                ->andWhere(':category IN (c)')
                ->setParameter('category', $category);
        }

        if (isset($tag)) {
            $postList = $postList
                ->join('p.tags', 't')
                ->andWhere(':tag IN (t)')
                ->setParameter('tag', $tag);
        }

        $postList->getQuery()
            ->getResult();

        $posts = $this->paginatorInterface->paginate($postList, $page, 9);

        return $posts;
    }
}
