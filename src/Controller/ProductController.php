<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response,Request};
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Category,Product};
use App\Handler\Utils;

class ProductController extends AbstractController
{
    private $em;
    private $translator;
    private $utils;
    public function __construct(EntityManagerInterface $em,TranslatorInterface $translator,Utils $utils )
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->utils = $utils;
    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'categories' => $this->em->getRepository(Category::class)->findAllInArray(),
        ]);
    }

    #[Route('/product/new', name: 'app_product_new')]
    public function new(): Response
    {
        return $this->render('product/new.html.twig', [
            'categories' => $this->em->getRepository(Category::class)->findAllInArray(),
        ]);
    }

    #[Route('/product/new/action', name: 'app_product_new_action')]
    public function newAction(Request $request): Response
    {
        $code = $request->get('code',null);
        $name = $request->get('name',null);
        $description = $request->get('description',null);
        $brand = $request->get('brand',null);
        $price = str_replace(".","", $request->get('price',null) );
        $category = $request->get('category',null);
        
        try{
            $data = [
                "category" => $this->em->getRepository(Category::class)->find($category),
                "code"=>$code,
                "name"=>$name,
                "description"=>$description,
                "brand"=>$brand,
                "price"=>$price,
                "createdAt"=> new \DateTime(),
                "updatedAt"=> new \DateTime(),
                "active"=>true
            ];

            $this->em->getRepository(Product::class)->createProduct($data);

            $this->addFlash('success',$this->translator->trans('success') );

        } catch (\Throwable $throwable) {
            $this->addFlash('error',$throwable->getMessage());
        }

        return $this->redirectToRoute('app_product_new');
    }

    #[Route('/product/update/{id}', name: 'app_product_update')]
    public function update($id): Response
    {
        return $this->render('product/update.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/list', name: 'app_product_list')]
    public function listproducts(Request $request): Response
    {
        $start = $request->get('start',0);
        $length = $request->get('length',10);
        $draw = $request->get('draw',1);
        $search = $request->get('search',null);
        $order = $request->get('order',null);

        $dataComplete = [];
        $dataQuery = ["start"=>$start,"length"=>$length,"filterValue"=>$search,"order"=>$order];
        
        foreach ($this->em->getRepository(Product::class)->findRegistersToList($dataQuery,false) as $key => $value) {
            $dataComplete[] = [
                $value["category"],
                $value["code"],
                $value["name"],
                $value["brand"],
                $value["price"],
                $value["createdAt"]->format("Y-m-d"),  
                $this->utils->getFormatStatus($value["active"],true),
                $value["id"],              
            ];
        }

        $data = [
            "draw"=> $draw,
            "recordsTotal"=>($length),
            "recordsFiltered"=>$this->em->getRepository(Product::class)->findRegistersToList($dataQuery,true),
            "data"=>$dataComplete
        ];
        return $this->json($data,200);
    }
}
