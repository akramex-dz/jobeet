<?php

namespace App\EventListener;

use App\Entity\Job;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class JobTokenListener
{
    /**
     * @param LifecycleEventArgs *args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject(); 
        
        if (!$entity instanceof Job) {
            return;
        }

        if (!$entity->getToken()) {
            $entity->setToken(\bin2hex(\random_bytes(10)));
        }
    }
}