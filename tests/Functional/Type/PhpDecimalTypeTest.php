<?php

declare(strict_types=1);

namespace Gilek\DoctrinePhpDecimal\Test\Functional\Type;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


final class PhpDecimalTypeTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();


// Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/db.sqlite',
        );

// obtaining the entity manager
        $entityManager = EntityManager::create($conn, $config);


    }
}