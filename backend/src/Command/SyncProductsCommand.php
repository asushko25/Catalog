<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:sync-products', description: 'Sync products from dummyjson.com')]
final class SyncProductsCommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface $http,
        private readonly ProductRepository $products,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resp = $this->http->request('GET', 'https://dummyjson.com/products?limit=100');
        $json = $resp->toArray(false);
        $items = $json['products'] ?? [];

        $countNew = 0; $countUpd = 0;

        foreach ($items as $row) {
            $extId = (string)($row['id'] ?? '');
            if ($extId === '') { continue; }

            $p = $this->products->findOneBy(['externalId' => $extId]) ?? new Product();
            $isNew = $p->getId() === null;

            $p->setExternalId($extId)
              ->setName((string)($row['title'] ?? $row['name'] ?? ''))
              ->setCategory((string)($row['category'] ?? 'misc'))
              ->setPriceGross((string) number_format((float)($row['price'] ?? 0), 2, '.', ''))
              ->setCurrency('PLN');

            if ($isNew) { $this->em->persist($p); $countNew++; }
            else { $countUpd++; }
        }

        $this->em->flush();

        $output->writeln(sprintf('Synced. New: %d, Updated: %d', $countNew, $countUpd));
        return Command::SUCCESS;
    }
}
