<?php 

namespace App\Controller\API;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Affiliate;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
class JobController extends AbstractFOSRestController 
{
    /**
     * @Rest\Get( "/{token}/jobs", name="api.job.list" )
     *
     * @Entity("affiliate", expr="repository.findOneActiveByToken(token)")
     *  
     * @param Affiliate $affiliate
     * @param JobRepository $jobrepository
     * 
     * @return Response
     */
    public function getJobsAction(Affiliate $affiliate, JobRepository $jobrepository) : Response
    {
        $jobs = $jobrepository->findActiveJobsForAffiliate($affiliate);

        $view = $this->view($jobs, Response::HTTP_OK);
        return $this->handleView($view);
    }
}
