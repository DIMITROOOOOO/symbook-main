<?php

namespace App\Repository;
use App\Entity\Livre;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }
    public function livrePlusVendu(\DateTime $startDate, \DateTime $endDate): array
    {
        $commandes = $this->createQueryBuilder('c')
            ->select('c.items')
            ->where('c.dateCommande BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        $bookSales = [];
        foreach ($commandes as $commande) {
            $items = $commande['items'];
            foreach ($items as $item) {
                $livreId = $item['livre_id'];
                $quantite = $item['quantite'];
                if (!isset($bookSales[$livreId])) {
                    $bookSales[$livreId] = 0;
                }
                $bookSales[$livreId] += $quantite;
            }
        }

        if (empty($bookSales)) {
            return ['titre' => 'No sales', 'totalSold' => 0];
        }

        arsort($bookSales);
        $mostSoldLivreId = key($bookSales);
        $totalSold = reset($bookSales);

        $livre = $this->getEntityManager()->getRepository(Livre::class)->find($mostSoldLivreId);
        return [
            'titre' => $livre ? $livre->getTitre() : 'Unknown Book',
            'totalSold' => $totalSold
        ];
    }
    public function recherParDate(\DateTime $startDate, \DateTime $endDate): array
    {
        $results = $this->createQueryBuilder('c')
            ->select('c.dateCommande as dateCommande, COUNT(c.id) as orderCount')
            ->where('c.dateCommande BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('c.dateCommande')
            ->orderBy('c.dateCommande', 'ASC')
            ->getQuery()
            ->getResult();

        // Process the dates in PHP to group by year-month
        $ordersByPeriod = [];
        foreach ($results as $result) {
            $date = $result['dateCommande'];
            $period = $date->format('Y-m'); // Format date as YYYY-MM
            if (!isset($ordersByPeriod[$period])) {
                $ordersByPeriod[$period] = 0;
            }
            $ordersByPeriod[$period] += $result['orderCount'];
        }

        // Convert to the format expected by the template
        $formattedResults = [];
        foreach ($ordersByPeriod as $period => $orderCount) {
            $formattedResults[] = [
                'period' => $period,
                'orderCount' => $orderCount
            ];
        }

        return $formattedResults;
    }
}