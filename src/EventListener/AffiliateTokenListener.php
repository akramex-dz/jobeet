<?php 

namespace App\EventListener;

use App\Entity\Affiliate;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AffiliateTokenListener 
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $affiliate = $args->getObject();

        if (!$affiliate instanceof Affiliate) {
            return;
        }

        if (!$affiliate->getToken()) {
            $affiliate->setToken(\bin2hex(\random_bytes(10)));
        }
    }     
}
