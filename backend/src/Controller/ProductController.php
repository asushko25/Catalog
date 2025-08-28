<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProductRepository $repo,
        private readonly ValidatorInterface $validator,
    ) {}

    #[Route('/products', name: 'app_products_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $items = array_map(
            fn(Product $p) => $this->serializeProduct($p),
            $this->repo->findBy([], ['id' => 'DESC'])
        );

        return $this->json($items, 200);
    }

    #[Route('/products', name: 'app_product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return $this->json(['message' => 'Invalid JSON'], 400);
        }

        $product = new Product();

        $externalId = isset($data['externalId']) && $data['externalId'] !== '' ? (string)$data['externalId'] : null;
        $product->setExternalId($externalId);

        $product
            ->setName((string)($data['name'] ?? ''))
            ->setCategory((string)($data['category'] ?? ''))
            ->setPriceGross($data['priceGross'] ?? '')
            ->setCurrency((string)($data['currency'] ?? ''));

        if ($product->getCreatedAt() === null) {
            $product->setCreatedAt(new \DateTimeImmutable());
        }

        if ($externalId !== null) {
            $exists = $this->repo->findOneBy(['externalId' => $externalId]);
            if ($exists) {
                return $this->json([
                    'message' => 'Validation failed',
                    'errors'  => [
                        ['field' => 'externalId', 'message' => 'externalId must be unique'],
                    ],
                ], 409);
            }
        }

        $violations = $this->validator->validate($product);
        if (\count($violations) > 0) {
            $errors = [];
            foreach ($violations as $v) {
                $errors[] = [
                    'field'   => $v->getPropertyPath() ?: null,
                    'message' => $v->getMessage(),
                ];
            }
            return $this->json(['message' => 'Validation failed', 'errors' => $errors], 422);
        }

        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'message' => 'Validation failed',
                'errors'  => [
                    ['field' => 'externalId', 'message' => 'externalId must be unique'],
                ],
            ], 409);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Cannot create product',
            ], 400);
        }

        return $this->json($this->serializeProduct($product), 201);
    }

    #[Route('/products/{id}/price', name: 'app_product_price', methods: ['GET'])]
    public function price(int $id, \App\Service\FinalPriceCalculator $calculator): JsonResponse
    {
        $product = $this->repo->find($id);
        if (!$product) {
            return $this->json(['message' => 'Not found'], 404);
        }

        $final = $calculator->calculate($product);

        return $this->json(['finalPrice' => $final], 200);
    }

    private function serializeProduct(Product $p): array
    {
        return [
            'id'         => $p->getId(),
            'externalId' => $p->getExternalId(),
            'name'       => $p->getName(),
            'category'   => $p->getCategory(),
            'priceGross' => $p->getPriceGross(),
            'currency'   => $p->getCurrency(),
            'createdAt'  => $p->getCreatedAt()?->format(\DateTimeInterface::ATOM),
        ];
    }
}
