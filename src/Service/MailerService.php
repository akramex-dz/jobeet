<?php 

namespace App\Service;

use App\Entity\Affiliate;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService 
{
    /**@var MailerInterface */
    private $mailer;

/**
 * @param MailerInterface $mailer
 */
public function __construct(MailerInterface $mailer)
{
    $this->mailer = $mailer;
}

/**
 * @param Affiliate $affiliate
 */
public function sendActivationEmail(Affiliate $affiliate)
{
    $email = (new TemplatedEmail())
            ->subject($affiliate->getUrl().'Jobeet affiliate account ACTIVATED !')
            ->from('akramexleom@gmail.com')
            ->to($affiliate->getEmail())
            ->htmlTemplate('emails/affiliate_activation.html.twig')
            ->context([
                'token'=> $affiliate->getToken(),
            ]);
        
    $this->mailer->send($email);
}

}
