<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/")
 */
class JobController extends AbstractController
{    
    /**
     * Lists all job entities.
     * 
     * @Route ("", name="job.list", methods = "GET")
     * 
     * @param CategoryRepository $categoryRepository
     * 
     * @return Response 
     */
    public function list(CategoryRepository $categoryRepository) : Response
    {
        $categories = $categoryRepository->findWithActiveCategory();
        return $this->render('job/list.html.twig',[
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity
     * 
     * @Route("job/{id<\d+>}", name="job.show", methods="GET")
     * 
     * @Entity("job", expr="repository.findActiveJob(id)")
     * 
     * @param Job $job
     * 
     * @return Response
     */ 
    public function show(Job $job)  : Response
    {
        return $this->render('job/show.html.twig',[
            'job'=>$job,
        ]);
    }
}