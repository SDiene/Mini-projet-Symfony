<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Service;
use App\Entity\Employer;
use App\Form\ServiceType;
use PhpParser\Node\Scalar\Encapsed;
use App\Repository\EmployerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function list(){

        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/form2", name="form2")
     * @Route("/form2/{id}/modif", name="modif")
     */
    public function formService(Service $service = null , Request $request, ObjectManager $manager)
    {
        if (!$service) {
            $service = new Service();
        }

        $job = $this->getDoctrine()->getRepository(Service::class);
        $reqservice = $job->findAll();

        // $form = $this->createForm(ServiceType::class, $service);

        $form = $this->createFormBuilder($service)

                    ->add('libelle')
                    ->getForm();

        $form->handleRequest($request);
        
        //dump($service);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($service);
            $manager->flush();

            // $this->addFlash('success', 'employé ajouté avec succée');

            return $this->redirectToRoute('form2',['id'=>$service->getId()]);
        }

        return $this->render('blog/form2.html.twig',[
            'forme' => $form->createView(),
            'reqserv'=> $reqservice,
            'modd'=> $service->getId()!==null
        ]);
    }

    /**
     * @Route("/form2/{id}/supser", name="supser")
     */

    public function delService(Service $service, ObjectManager $manager){
        
        $manager->remove($service);
        $manager->flush();
        return $this->redirectToRoute('form2',['id'=>$service->getId()]);
    }

    /**
     * @Route("/form1", name="form1")
     * @Route("/form1/{id}/modif", name="form")
     */
    public function formEmployer(Employer $employer = null , Request $request, ObjectManager $manager)
    {
        // $employer = new Employer();

        if (!$employer) {
            $employer = new Employer();
        }
        
        $soce = $this->getDoctrine()->getRepository(Employer::class);
        $reqemployer = $soce->findAll(); 

        $form = $this->createFormBuilder($employer)

                     ->add('matricule')
                     ->add('nomcomplet')
                     ->add('datenaissance',DateType::class,[
                        'widget' => 'single_text'
                    ])
                     ->add('salaire')
                     ->add('idservice',EntityType::class, [
                         'class'=>  Service::class, 'choice_label'=> 'libelle'

                     ])
                     ->getform();

        $form->handleRequest($request);

        //dump($employer);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($employer);
            $manager->flush();

        }
        
        return $this->render('blog/form1.html.twig',[
            'employerform' => $form->createView(),
            'reqemp' => $reqemployer,
            'modd'=> $employer->getId()!==null
        ] );
    }

   /**
     * @Route("/form1/{id}/supempl", name="supempl")
     */

    public function delEmployer(Employer $employer = null, ObjectManager $manager){
        
        $manager->remove($employer);
        $manager->flush();
        return $this->redirectToRoute('form1',['id'=>$employer->getId()]);

    }
}
