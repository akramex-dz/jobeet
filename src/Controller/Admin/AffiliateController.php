<?php 

namespace App\Controller\Admin;

use App\Entity\Affiliate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AffiliateRepository;
use App\Service\MailerService;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin")
 */
class AffiliateController extends AbstractController
{
    /**
     * Lists all affiliates
     * 
     * @Route("/affiliates/{page<\d+>}",
     * name="admin.affiliate.list",
     * methods="GET",
     * defaults={"page":1}
     * )
     * 
     * @param AffiliateRepository $affiliaterepo
     * @param PaginatorInterface $paginator
     * @param int $page
     * 
     * @return Response
     */
    public function list(AffiliateRepository $affiliaterepo, PaginatorInterface $paginator, int $page): Response
    {
        $affiliates = $paginator->paginate(
            $affiliaterepo->findAll(),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'a.active',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'ASC',
            ]
        );
        
        return $this->render('admin/affiliate/list.html.twig',[
            'affiliates' => $affiliates,
        ]);
    }

    /**
     * Activate a certain affiliate
     * 
     * @Route("Affiliate/{id}/activate", name="admin.affiliate.activate", methods="GET", requirements={"id" = "\d+"})
     * 
     * @param EntityManagerInterface $em
     * @param Affiliate $affiliate
     * @param \Swift_Mailer $mailer
     * @param MailerService $mailerService
     * 
     * 
     * @return Response
     */
    public function activate(Affiliate $affiliate, EntityManagerInterface $em, \Swift_Mailer $mailer, MailerService $mailerService) : Response
    {
        $affiliate->setActive(true);
        $em->flush($affiliate);

        $mailerService->sendActivationEmail($affiliate);
        
        return $this->redirectToRoute('admin.affiliate.list');
    }

    /**
     * Desactivate a certain affiliate
     * 
     * @Route("Affiliate/{id}/desactivate", name="admin.affiliate.desactivate", methods="GET", requirements={"id" = "\d+"})
     * 
     * @param EntityManagerInterface $em
     * @param Affiliate $affiliate
     * 
     * @return Response
     */
    public function desactivate(Affiliate $affiliate, EntityManagerInterface $em) : Response
    {
        $affiliate->setActive(false);
        $em->flush($affiliate);

        return $this->redirectToRoute('admin.affiliate.list');
    }
}