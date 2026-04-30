<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }
#[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER');

    $invoice = new Invoice();
    $form = $this->createForm(InvoiceType::class, $invoice);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // 1. On lie la facture à l'utilisateur connecté
        $invoice->setOwner($this->getUser());
        
        // 2. Gestion du statut en fonction du bouton cliqué
        // Assure-toi que Invoice::PENDING et Invoice::DRAFT existent bien comme constantes dans ton entité Invoice !
        // Sinon, utilise des chaînes de caractères : 'PENDING' et 'DRAFT'
        if ($request->request->has('action_validate')) {
            $invoice->setStatus(Invoice::PENDING);
        } else {
            $invoice->setStatus(Invoice::DRAFT);
        }

        // 3. On sauvegarde en base de données
        $entityManager->persist($invoice);
        $entityManager->flush();

        // 4. On redirige vers la liste des factures
        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('invoice/new.html.twig', [
        'invoice' => $invoice,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/validate', name: 'app_invoice_validate', methods: ['POST'])]
    public function validate(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('validate-'.$invoice->getId(), $request->request->get('_token'))) {
            $invoice->setStatus(Invoice::PENDING);
            $entityManager->flush();
            $this->addFlash('success', 'Facture validée, en attente de paiement.');
        }
        return $this->redirectToRoute('app_invoice_show', ['id' => $invoice->getId()]);
    }

    #[Route('/{id}/pay', name: 'app_invoice_pay', methods: ['POST'])]
    public function pay(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('pay-'.$invoice->getId(), $request->request->get('_token'))) {
            $invoice->setStatus(Invoice::PAID);
            $entityManager->flush();
            $this->addFlash('success', 'Paiement validé.');
        }
        return $this->redirectToRoute('app_invoice_show', ['id' => $invoice->getId()]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
