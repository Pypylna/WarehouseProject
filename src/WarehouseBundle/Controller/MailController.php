<?php namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use WarehouseBundle\Form\MailType;


class MailController extends Controller
{
	/**
	 * @Route("/user/mail", name="user_mail")
	 */
	public function contactAction(Request $request)
	{
		$mailer = $this->get('mailer');
		$message = $mailer->createMessage();
		
		$form=$this->createForm(new MailType, $message, array(
			'action' => $this->generateUrl('user_mail'),
			'method' => 'POST',
		));
		
		$form->handleRequest($request);
		if($form->isValid()){
			$message->setTo('pauptest@gmail.com');
			$mailer->send($message);
			#error - w mailu nie wyswietla siê od kogo dosta³am wiadomoœæ
			# problem po stronie gmaila?
			
			#todo - widok
			return $this->render('base.html.twig');
		}
		return $this->render('mail/mail.html.twig', array(
			'form'=>$form->createView(),
		));
	}
	
	
}