<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Repository\CategoryRepository;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use App\Service\JobHistoryService;

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
     * 
     * @param CategoryRepository $categoryRepository
     * @param JobHistoryService $jobHistory
     * 
     * @return Response 
     */
    public function list(CategoryRepository $categoryRepository, JobHistoryService $jobHistory) : Response
    {
        $categories = $categoryRepository->findWithActiveCategory();

        return $this->render('job/list.html.twig',[
            'categories' => $categories,
            'historyJobs'=> $jobHistory->getJobs()
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
     * @param JobHistoryService $history
     * 
     * @return Response
     */ 
    public function show(Job $job, JobHistoryService $history)  : Response
    {
        $history->addJob($job);
        
        return $this->render('job/show.html.twig',[
            'job'=>$job,
        ]);
    }

    /**
     * Creates a new job entity.
     * 
     * @Route("/job/create", name="job.create", methods={"GET", "POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em 
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            };

            $em->persist($job);
            $em->flush();
            
            return $this->redirectToRoute('job.preview',[
                'token' => $job->getToken()
            ]);
        }
        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Edit existiong job entity
     * 
     * @Route("/job/{token<\w+>}/edit", name="job.edit", methods={"GET", "POST"} )
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * 
     * @return Response 
     */
    public function edit (Request $request, Job $job, EntityManagerInterface $em) : Response 
    {
            $form = $this->createForm(JobType::class, $job);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() ) {
                $em->flush();

                return $this->redirectToRoute('job.list');
            }

            return $this->render('job/edit.html.twig',[
                'form' => $form->createView()
            ]);
    }

    /**
     * Finds and displays the preview page for a job
     * 
     * @Route("job/{token<\w+>}", name="job.preview", methods="GET")
     * 
     * @param Job $job
     * 
     * @return Response
     */
    public function preview(Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);
        return $this->render('job/show.html.twig',[
            'job'=>$job,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
            'publishForm' => $publishForm->createView()
        ]);
    }
    
    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="job.delete", methods="POST", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Job $job, EntityManagerInterface $em) : Response
    {
        $em->remove($job);
        $em->flush($job);        
        $this->addFlash('success', 'job removed with successs');
        return $this->redirectToRoute('job.list');
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder($job)
            ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Publish a job entity.
     *
     * @Route("job/{token}/publish", name="job.publish", methods="POST", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function publish(Job $job, EntityManagerInterface $em) : Response
    {
        $job->setActivated(true);
        $em->flush();
        $this->addFlash('notice', 'Your job was published');
        return $this->redirectToRoute('job.preview', [
            'token' => $job->getToken(),
        ]);
    }

    /**
     * Creates a form to publish a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createPublishForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
            ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
            ->setMethod('POST')
            ->getForm();
    }
}