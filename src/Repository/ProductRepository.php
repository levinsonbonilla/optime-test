<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Handler\Utils;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $columns;
    private $utils;

    public function __construct(ManagerRegistry $registry,Utils $utils)
    {
        parent::__construct($registry, Product::class);
        $this->utils = $utils;
        $this->columns = [
            "0" => "c.name",
            "1" => "p.code",
            "2" => "p.name",
            "3" => "p.brand",
            "4" => "p.price",
            "5" => "p.createdAt",
            "6" => "p.active",
            "7" => "p.id",
            "8" => "c.id"
        ];
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createProduct(array $data){
        extract($data);

        $product = new Product();
        $product->setCode($code);
        $product->setName($name);
        $product->setCategory($category);
        $product->setDescription($description);
        $product->setBrand($brand);
        $product->setPrice($price);
        $product->setCreatedAt($createdAt);
        $product->setUpdatedAt($updatedAt);
        $product->setActive($active);

        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
        
        return $product;
    }

         
    public function findRegistersToList($data,$isCount = false)
    {
        extract($data);
        $query = 
        $this->createQueryBuilder('p');

        if ($isCount) {
            $query
            ->select("COUNT(c)");
        }else {
            $query
            ->select("c.name as category, p.code,p.name,p.brand,p.price,p.createdAt,p.active,p.id,c.id as idCategory");
        }
        
        $query 
        ->join("p.category","c");
                
        $query = $this->utils->filtersDataTable($query,$data,$this->columns,$isCount,true);        
        
        if ($isCount) {
            return $query
            ->getQuery()
            ->getSingleScalarResult();
        }else {
            return $query
            ->getQuery()
            ->getResult();
        }
    }

}
