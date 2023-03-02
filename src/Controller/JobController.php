<?php 

namespace App\Controller;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/")
 */
class JobController extends AbstractController
{    
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Lists all job entities.
     * 
     * @Route ("", name="job.list", methods = "GET")
     * 
     * @return Response 
     */
    public function list() : Response
    {
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();
        return $this->render('job/list.html.twig', [
            'jobs'=>$jobs
        ]);
    }

    /**
     * Finds and displays a job entity
     * 
     * @Route("job/{id<\d+>}", name="job.show", methods="GET")
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