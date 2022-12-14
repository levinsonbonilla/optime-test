<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    private $validator;

    public function __construct(ManagerRegistry $registry,Utils $utils,ValidatorInterface $validator )
    {
        parent::__construct($registry, Product::class);
        $this->utils = $utils;
        $this->validator = $validator;
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

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return $errors;
        }

        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
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

    public function findById($productId)
    {
       return $this->createQueryBuilder('p')
           ->select("p.id,c.id as category, p.code, p.name, p.description, p.brand, p.price, p.active")
           ->join("p.category","c")
           ->where("p.id = :productId")
           ->setParameter("productId",$productId)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }

    public function updateProduct(array $data){        
        extract($data);
        
        $product = $this->find($id);
        $product->setCode($code);
        $product->setName($name);
        $product->setCategory($category);
        $product->setDescription($description);
        $product->setBrand($brand);
        $product->setPrice($price);
        $product->setUpdatedAt($updatedAt);
        $product->setActive($active);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return $errors;
        }

        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
    } 

}
