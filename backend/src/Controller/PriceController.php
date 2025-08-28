<?php
namespace App\Controller;

use App\Domain\FinalPriceCalculator;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PriceController
{
    public function __construct(private EntityManagerInterface $em, private FinalPriceCalculator $calc) {}

    #[Route('/api/products/{id}/price', methods: ['GET'])]
    public function price(int $id): JsonResponse {
        $p = $this->em->getRepository(Product::class)->find($id);
        if (!$p) return new JsonResponse(['error'=>'Not found'], 404);
        return new JsonResponse(['finalPrice'=>$this->calc->calculate($p)]);
    }
}
