<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }


    public function gamesCount(): ?int
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //Fetch unplayed games in the week
    public function unPlayedGames(int $week)  {
        return $this->createQueryBuilder('g')
            ->where('g.awayScore IS NULL')
            ->andWhere('g.homeScore IS NULL')
            ->andWhere('g.week = :week')
            ->setParameter('week', $week)
            ->getQuery()
            ->execute();
    }

}
