<?php 

namespace App\Service;

use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Job;

class JobHistoryService 
{
    private const MAX = 3;

    /**@var SessionInterface */
    private $session;

    /**@var JobRepository */
    private $jobRepo;

    /**
     * @param SessionInterface $session
     * @param JobRepository $jobRepo
     */
    public function __construct(SessionInterface $session, JobRepository $jobRepo)
    {
        $this->session = $session;
        $this->jobRepo = $jobRepo;
    }

    /**
     * Adds a job to history
     * 
     * @param Job $job 
     * 
     * @return void
     */
    public function addJob(Job $job) : void
    {
        //get the jobIds from session
        $jobs = $this->getJobIds();
        
        //add the job id to the begining of the array 
        array_unshift($jobs, $job->getId());
        
        //Remove duplication 
        $jobs = array_unique($jobs);
        
        //Get the first three elements 
        $jobs = array_slice($jobs, 0, self::MAX);

        //Set the jobIds  in the session
        $this->session->set('job_history', $jobs);
    }

    /**
     * Returns the history array
     * 
     * @return Job[]
     */
    public function getJobs() : array
    {
        $jobs = [];
        foreach ($this->getJobIds() as $jobId) {
            $jobs[] = $this->jobRepo->findActiveJob($jobId);
        }

        return array_filter($jobs);
    }

    /**
     * returns the array from the session 
     * 
     * @return array
     */
    public function getJobIds() : array
    {
        return $this->session->get('job_history', []);

    }
}
