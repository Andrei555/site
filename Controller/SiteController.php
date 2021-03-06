<?php

namespace Controller;


use Library\Controller;
use Library\Request;
use Model\Form\ContactForm;
use Model\Feedback;
use Library\Session;


class SiteController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('index.phtml');
    }

    public function contactAction(Request $request)
    {
        $form = new ContactForm($request);
        //todo: inject
        $repo = $this->container->get('repository_manager')->getRepository('Feedback');

        if ($request->isPost()){
            if ($form->isValid()) {
                $feedback = (new Feedback())
                    ->setName($form->clean($form->username))
                    ->setEmail($form->clean($form->email))
                    ->setMessage($form->clean($form->message))
                    ->setIpAddress($request->getIpAddress());
                $repo->save($feedback);
                Session::setFlash('Данные сохранены!');
                $this->container->get('router')->redirect('/contact-us');
            }
            Session::setFlash('Форма заполненна не коректно!');
    }
        $comments = $repo->showComments();
        return $this->render('contact.phtml', ['form' => $form, 'comments' => $comments]);
    }
}