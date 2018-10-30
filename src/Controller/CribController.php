<?php

namespace App\Controller;


use App\Entity\Crib;
use App\Entity\CribContent;
use App\Form\Type\CribType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CribController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/index/{field}-{direction}", name="index")
     * @param $field
     * @param $direction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($field = 'id', $direction = 'DESC', Request $request)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);

        $form =
            $this
                ->createFormBuilder()
                ->add('searchField', TextType::class, ['label' => false])
                ->add('submit', SubmitType::class, ['label' => 'search', 'attr' => ['class' => 'Link Link--edit']])
                ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $searchString = $form->getData()['searchField'];
            $cribs        = $cribRepository->findBySearchString($searchString);
        } else {
            $cribs = $cribRepository->findBy(
                [],
                [
                    $field => $direction,
                ]
            );
        }

        return $this->render(
            'cribs/index.html.twig',
            [
                'cribs' => $cribs,
                'form'  => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/new", name="new")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {
        $crib = new Crib();

        $cribContent = new CribContent();

        $crib->addCribContent($cribContent);

        $form = $this->createForm(CribType::class, $crib);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crib);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'cribs/new_edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/show/{id}", name="show")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $crib           = $cribRepository->find($id);

        return $this->render(
            'cribs/show.html.twig',
            [
                'crib' => $crib,
            ]
        );
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Crib                          $crib
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Crib $crib)
    {
        $form = $this->createForm(CribType::class, $crib);

        $crib->setEditDate(new \DateTime('now'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('show', ['id' => $crib->getId()]);
        }

        return $this->render(
            'cribs/new_edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id, Request $request)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $crib           = $cribRepository->find($id);

        $form = $this
            ->createFormBuilder()
            ->add('submit', SubmitType::class, ['label' => 'Delete', 'attr' => ['class' => 'Link Link--delete']])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crib);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'cribs/delete.html.twig',
            [
                'form' => $form->createView(),
                'crib' => $crib,
            ]
        );
    }
}
