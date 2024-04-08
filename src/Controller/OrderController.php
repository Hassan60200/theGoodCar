<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(private readonly OrderRepository $orderRepository, private readonly CarRepository $carRepository)
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

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
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
                'car' => $car,
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
