<?php

declare(strict_types=1);

namespace Gilek\DoctrinePhpDecimal\Test\Functional\Type;

use Decimal\Decimal;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Gilek\DoctrinePhpDecimal\Test\Db\Entity\Test;
use Gilek\DoctrinePhpDecimal\Type\PhpDecimalType;
use PDOException;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

final class PhpDecimalTypeTest extends TestCase
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../../Db/Entity'],
            true,
            null,
            null,
            false
        );

        $conn = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../Db/db.sqlite'
        ];

        $this->entityManager = EntityManager::create($conn, $config);
        if (!Type::hasType(PhpDecimalType::NAME)) {
            Type::addType(PhpDecimalType::NAME, PhpDecimalType::class);
        }

        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->rollback();
    }

    /**
     * @test
     */
    public function test(): void
    {
        /** @var EntityRepository<Test> $repository */
        $repository = $this->entityManager->getRepository(Test::class);

        /** @var Test $entity */
        $entity = $repository->find(1);
        $this->assertSame('1.23450', $entity->getValueString()->toFixed(5));
        $this->assertSame('1.00000', $entity->getValueInteger()->toFixed(5));
        $this->assertSame('1.23450', $entity->getValueFloat()->toFixed(5));
    }

    /**
     * @texst
     */
    public function test2(): void
    {
        $entity = (new Test())
            ->setValueString(new Decimal('5.4321'))
            ->setValueFloat(new Decimal('5.4321'))
            ->setValueInteger(new Decimal('5'));

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->refresh($entity);

        $this->assertSame('5.43210', $entity->getValueString()->toFixed(5));
        $this->assertSame('5.00000', $entity->getValueInteger()->toFixed(5));
        $this->assertSame('5.43210', $entity->getValueFloat()->toFixed(5));
    }
}