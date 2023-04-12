<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Post\Category;
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
     *
     * @return PaginationInterface
     */
    public function findPublished(Int $page, ?Category $category = null): PaginationInterface
    {
        $postList = $this->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categories', 'c')
            ->where('p.state LIKE :state')
            ->setParameter('state', Post::STATE_LIST[1])
            ->orderBy('p.createdAt', 'DESC');

        if (isset($category)) {
            $postList = $postList
                ->andWhere('c.id LIKE :category')
                ->setParameter('category', $category->getId());
        }

        $postList->getQuery()
            ->getResult();

        $posts = $this->paginatorInterface->paginate($postList, $page, 9);

        return $posts;
    }
}
