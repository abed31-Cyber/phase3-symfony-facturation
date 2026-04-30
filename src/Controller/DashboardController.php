<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function index(
        InvoiceRepository $invoiceRepo,
        ClientRepository  $clientRepo,
        ProductRepository $productRepo,
    ): Response {
        $user = $this->getUser();

        $allInvoices = $invoiceRepo->findBy(['owner' => $user]);
        $clients     = $clientRepo->findBy(['owner' => $user]);
        $products    = $productRepo->findBy(['owner' => $user]);

        $totalPaid    = 0.0;
        $pendingCount = 0;
        $monthlyData  = array_fill(1, 12, 0.0);

        foreach ($allInvoices as $invoice) {
            if ($invoice->getStatus() === Invoice::PAID) {
                $totalPaid += $invoice->getTotalTtc() ?? 0;
                $month = (int) $invoice->getCreatedAt()->format('n');
                $monthlyData[$month] += $invoice->getTotalTtc() ?? 0;
            }
            if ($invoice->getStatus() === Invoice::PENDING) {
                $pendingCount++;
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'totalPaid'    => $totalPaid,
            'pendingCount' => $pendingCount,
            'clientCount'  => count($clients),
            'productCount' => count($products),
            'monthlyData'  => array_values($monthlyData),
        ]);
    }
}
