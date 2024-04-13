<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    private $gateway;

    public function __construct(private readonly CarRepository $carRepository, private readonly EntityManagerInterface $em)
    {
        $this->gateway = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    #[Route('/stripe/checkout/{reference}', name: 'app_stripe_checkout')]
    public function checkout(SessionInterface $session, $reference): Response
    {
        $cart = $session->get('cart', []);
        $order = $this->em->getRepository(Order::class)->findOneByReference($reference);

        $lineItems = [];
        foreach ($cart as $item) {
            $car = $this->carRepository->find($item['id']);
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => ($car->getPrice() * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $session = $this->gateway->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_stripe_success', [], 0),
            'cancel_url' => $this->generateUrl('app_stripe_cancel', [], 0),
        ]);

        $order->setStripeId($session->id)
            ->setStatus('Sell');
        $this->em->persist($order);
        $this->em->flush();

        return $this->redirect($session->url);
    }

    #[Route('/stripe/success', name: 'app_stripe_success')]
    public function successOrder(SessionInterface $session): Response
    {
        $session->remove('cart');

        return $this->render('order/success.html.twig');
    }

    #[Route('/stripe/cancel', name: 'app_stripe_cancel')]
    public function cancelOrder(): Response
    {
        return $this->render('order/cancel.html.twig');
    }
}
