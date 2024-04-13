<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CarRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(private readonly OrderRepository $orderRepository,
        private readonly CarRepository $carRepository,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    #[
        Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orderRepository->findAll(),
        ]);
    }

    #[Route('/cart', name: 'app_cart')]
    public function showCart(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $cars = [];

        foreach ($cart as $item) {
            $car = $this->carRepository->find($item['id']);
            $cars[] = [
                'car' => $car,
                'quantity' => $item['quantity'],
            ];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'cars' => $cars,
        ]);
    }

    #[Route('/cart/checkout/', name: 'app_cart_checkout')]
    public function validateBeforeCheckout(SessionInterface $session): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $cart = $session->get('cart', []);
        $dateNow = new \DateTime('now');
        $reference = $dateNow->format('dmY').'-'.uniqid();

        $order = new Order();
        $order->setUser($this->getUser())
            ->setReference($reference)
            ->setStatus('PENDING')
            ->setPurchaseAt(new \DateTimeImmutable('now'))
            ->setCreatedAt(new \DateTimeImmutable('now'));

        foreach ($cart as $item) {
            $car = $this->carRepository->find($item['id']);

            $order->addCar($car)
                ->setPrice($car->getPrice());
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_stripe_checkout', ['reference' => $reference]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, SessionInterface $session): Response
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }

        $cart = $session->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = [
                'quantity' => 1,
                'id' => $id,
                'name' => $car->getBrand()->getName().' '.$car->getCarModel()->getName(),
            ];
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart', [], 301);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);

            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('app_cart');
    }
}
