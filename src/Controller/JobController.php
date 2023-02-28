<?php 

namespace App\Controller;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @Route("Job")
 */
class JobController extends AbstractController
{    
    /**
     * Lists all job entities.
     * 
     * @Route ("/", name="job.list")
     * 
     * @return Response 
     */
    public function list(EntityManagerInterface $entityManager) : Response
    {
        $jobRepository = $entityManager->getRepository(Job::class);
        $jobs = $jobRepository->findAll();
        return $this->render('job/list.html.twig', [
            'jobs'=>$jobs
        ]);
    }

    /**
     * Finds and displays a job entity.
     * 
     * @Route("/{id}", name="job.show")
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