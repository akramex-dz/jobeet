<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Admin\CategoryType;

/**
 * @Route("/admin")
 */
class CategoryController extends AbstractController
{
    /**
     * Lists all categories entities.
     *
     * @Route("/categories", name="admin.category.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Create category.
     *
     * @Route("/category/create", name="admin.category.create", methods="GET|POST")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create( Request $request, EntityManagerInterface $em) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush($category);

            return $this->redirectToRoute('admin.category.list');
        };
        
        return $this->render('admin/category/create.html.twig',[
            'form'=> $form->createView(),
        ]);
    }

    /**
     * Edit a category
     * 
     * @Route("/category/{id<\d+>}/edit", name="admin.category.edit", methods="GET|POST")
     * 
     * @param EntityManagerInterface $em
     * @param Category $category
     * @param Request $request 
     * 
     * @return Response
     */
    public function edit(EntityManagerInterface $em, Category $category, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush($category);

            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/edit.html.twig',[
            'category' => $category,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * Deletes a category by ID 
     * 
     * @Route("/category/{id<\d+>}/delete", name="admin.category.delete", methods="POST")
     * 
     * @param EntityManagerInterface $em
     * @param Category $category
     * @param Request $request
     * 
     * @return Response 
     */
    public function delete( EntityManagerInterface $em, Category $category, Request $request)
    {
            $em->remove($category);
            $em->flush($category);

            $this->addFlash('success','The category was removed successfully');
            return $this->redirectToRoute('admin.category.list');
    }

}