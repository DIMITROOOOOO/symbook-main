<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
final class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(Request $request,CommandeRepository $commandeRepository): Response
    {   $endDate = new \DateTime();
        $startDate = (clone $endDate)->modify('-6 months');
        if ($request->query->has('startDate') && $request->query->has('endDate')) {
            try {
                $startDate = new \DateTime($request->query->get('startDate'));
                $endDate = new \DateTime($request->query->get('endDate'));
            } catch (\Exception $e) {
                $this->addFlash('error', 'Invalid date range selected. Using default range.');
            }
        }
        $mostSoldBook = $commandeRepository->livrePlusVendu($startDate, $endDate);
        $ordersPerPeriod = $commandeRepository->recherParDate($startDate, $endDate);
        return $this->render('admin/dashboard.html.twig', [
            'mostSoldBook' => $mostSoldBook,
            'ordersPerPeriod' => $ordersPerPeriod,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }
}
