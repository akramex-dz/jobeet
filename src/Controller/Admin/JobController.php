<?php 

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("admin/")
 */
class JobController extends AbstractController
{
    /**
     * lists all Job Entities
     * 
     * @Route("jobs/{page<\d+>}",
     *  name = "admin.job.list",
     *  methods = "GET",
     *  defaults={"page": 1},
     *   )
     * 
     * @param JobRepository $jobRepo
     * @param PaginatorInterface $paginator
     * @param int $page
     * 
     * 
     * @return Response
     */
    public function list(JobRepository $jobRepo, PaginatorInterface $paginator, int $page): Response
    {
        $jobs = $paginator->paginate(
            $jobRepo->createQueryBuilder('j'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'j.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'DESC',
            ]
        );

        return $this->render('admin/job/list.html.twig',[
            'jobs'=> $jobs,
        ]);
    }

    /**
     * Creates a Job 
     * 
     * @Route("job/create", name= "admin.job.create", methods="GET|POST")
     * 
     * @param EntityManagerInterface $em
     * @param Request $request
     * 
     * @return Response
     */
    public function create(EntityManagerInterface $em, Request $request) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $em->persist($job);
            $em->flush();
        
            $this->addFlash('notice','Job created successfully');
            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a job
     * 
     * @Route("job/{id<\d+>}/edit", name="admin.job.edit", methods="GET|POST")
     * 
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Job $job
     * 
     * @return Response
     */
    public function edit(EntityManagerInterface $em, Request $request, Job $job) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush($job);

            $this->addFlash(
               'notice',
               'The job was successfully added'
            );
            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a job 
     * 
     * @Route("job/{id<\d+>}/delete", name="admin.job.delete", methods="POST")
     * 
     * @param EntityManagerInterface $em
     * @param Job $job
     * 
     * @return Response
     */
    public function delete(EntityManagerInterface $em, Job $job) : Response
    {
        $em->remove($job);
        $em->flush();
        
        $this->addFlash('notice', 'Job deleted !');
        return $this->redirectToRoute('admin.job.list');
  }
}
