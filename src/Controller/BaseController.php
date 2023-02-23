<?php

namespace App\Controller;

use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Provider;
use DateTimeImmutable;
use Symfony\Component\Form\Form;

class BaseController extends AbstractController
{

    private $providerRepository;

    public function __construct (ProviderRepository $providerRepository){
        $this->providerRepository = $providerRepository;
    }


    /**
     * @Route("/", name="getProviders")
     */
    public function getProviders(): Response
    {
        $providers = $this->providerRepository->findAll();

        return $this->render('base/listproviders.html.twig',[ 
            'providers' => $providers
        ]);
    }


    /**
     * @Route("/add", name="addProvider")
     */
    public function addProvider(Request $req): Response
    {

        $provider = new Provider();
        $form = $this->makeForm("Crear Proveedor",$provider);
        
        
            if($req->isMethod('POST')){

                $form->handleRequest($req);
                $provider=$form->getData();

                $intro_date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $provider->setIntroDate($intro_date);
                
                $this->providerRepository->add($provider);

                return $this->redirectToRoute('getProviders');
            }
        
        return $this->render('base/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }



     /**
     * @Route("/update/{id}", name="updateProvider")
     */
    public function updateProvider(int $id,Request $req): Response
    {
        $provider = $this->providerRepository->find($id);
        if (!$provider) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $form = $this->makeForm("Actualizar Proveeder",$provider);
        
            if($req->isMethod('POST')){

                $form->handleRequest($req);
                $provider=$form->getData();

                $update_date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $provider->setUpdateDate($update_date);
                
                $this->providerRepository->update();

                return $this->redirectToRoute('getProviders');
            }

        return $this->render('base/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="removeProvider")
     */
    public function removeProvider(int $id): Response
    {
        $provider = $this->providerRepository->find($id);
        $this->providerRepository->remove($provider);

        return $this->redirectToRoute('getProviders');

    }


    public function makeForm ($text,$provider) : Form{
        return $form = $this->createFormBuilder(($provider))
        ->add('Name',TextType::class,[
            'attr'=>[
                'class' => 'form-name'
            ]
        ])
        ->add('Email',TextType::class,[
            'attr'=>[
                'class' => 'form-email'
            ]
            
        ])
        ->add('Phone',TextType::class,[
            'attr'=>[
                'class' => 'form-phone'
            ]
        ])
        ->add('Type', ChoiceType::class, [

            'choices' => [
                'Hotel' => 'HOTEL',
                'Pista' => 'PISTA',
                'Complemento' => 'COMPLEMENTO',
            ],
            'expanded' => false,
        ],[
            'attr'=>[
                'class' => 'form-type'
            ]
            
        ])
        ->add('Active', CheckboxType::class, [
            'label' => 'Activo',
            'required' => false, 
            'attr'=>[
                 'class' => 'form-active'
            ],
            'label_attr' => [
                'class' => 'checkbox-label',
            ],
        ])
        ->add($text,SubmitType::class, ['label' => $text],[
            'attr'=>[
                 'class' => 'form-btn'
            ]
        ])
        ->getForm();
    }

    
}
