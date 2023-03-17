<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/category")
 */
class CategoryController extends AbstractController 
{
    /**
     * @Route(
     *      "/{slug}/{page}", 
     *      name="category.show", 
     *      methods="GET",
     *      defaults={"page"=1},
     *      requirements={"page"="\d+"}
     * )
     * 
     * @param Category $category
     * @param PaginatorInterface $paginator
     * @param int $page
     * @param EntityManagerInterface $em
     * 
     * @return Response
     */
    public function show(Category $category, PaginatorInterface $paginator, int $page, EntityManagerInterface $em): Response
    {
        $activeJobs = $paginator->paginate(
            $em->getRepository(Job::class)->getPaginatedActiveJobsByCategoryQuery($category),
            $page, //page
            $this->getParameter('max_jobs_on_category') //number of elts   
        );

        return $this->render('category/show.html.twig',[
            'category'=>$category,
            'activeJobs'=>$activeJobs
        ]);
    }

    
}