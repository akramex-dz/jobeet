<?php 
namespace App\Controller;

use App\Entity\Affiliate;
use App\Form\AffiliateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/affiliate")
 */
class AffiliateController extends AbstractController
{
    /**
     * Creates an affiliate
     * 
     * @Route("/create", name="affiliate.create", methods={"GET","POST"})
     * 
     * @param EntityManagerInterface $em
     * @param Request $request
     * 
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : RedirectResponse|Response
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $affiliate->setActive(false);

            $em->persist($affiliate);
            $em->flush($affiliate);

            return $this->redirectToRoute('affiliate.wait');
        }

        return $this->render('affiliate/create.html.twig',[
            "form" => $form->createView()
        ]);
    }

    /**
    * Shows the wait affiliate message.
    *
    * @Route("/affiliate/wait", name="affiliate.wait",  methods={"GET"})
    *
    * @return Response
    */
    public function wait()
    {
        return $this->render('affiliate/wait.html.twig');
    }
}