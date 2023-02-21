<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Provider;
use DateTimeImmutable;


class BaseController extends AbstractController
{

    /**
     * @Route("/add", name="addProvider")
     */
    public function addProvider(Request $req,EntityManagerInterface $em): Response
    {
        if($req->isMethod('POST')){
            $name = $req->request->get('name');
            $email = $req->request->get('email');
            $phone = $req->request->get('phone');
            $type = $req->request->get('type');
            $active = $req->request->get('active');
            $intro_date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $provider = new Provider();
            $provider->setName($name);
            $provider->setEmail($email);
            $provider->setPhone($phone);
            $provider->setType($type);
            $provider->setActive($active);
            $provider->setIntroDate($intro_date);

            
            $em->persist($provider);
            $em->flush();
            
        }
        
        return $this->render('base/index.html.twig');
    }

    /**
     * @Route("/get", name="getAllProviders")
     */
    public function getAllProviders(): Response
    {
        $em = $this->getDoctrine().getManager();
        $providers = $em->getRepository(Provider::class)->findAll();

        return this->render('base/index.html.twig');
    }

     /**
     * @Route("/update/{id}", name="updateProvider")
     */
    public function updateProvider(int $id): Response
    {
        $em = $this->getDoctrine().getManager();
        $provider = $em->getRepository(Provider::class)->find($id);
        
        if (!$provider) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        em->flush();
    }

    /**
     * @Route("/remove/{id}", name="removeProvider")
     */
    public function removeProvider(int $id): Response
    {
        $em = $this->getDoctrine().getManager();
        $provider = $em->getRepository(Provider::class)->find($id);
        $em->remove($provider);
    }

    /**
     * @Route("/", name="base")
     */
    public function index(): Response
    {
        return $this->render('base/index.html.twig');
    }
}
