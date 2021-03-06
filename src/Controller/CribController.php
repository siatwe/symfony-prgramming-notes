<?php

namespace App\Controller;

use App\Entity\Crib;
use App\Entity\CribContent;
use App\Form\Type\CribType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/index/{field}/{direction}", name="index")
     * @param Request $request
     * @param string $field
     * @param string $direction
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, $field = 'date', $direction = 'DESC')
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);

        $form = $this
            ->createFormBuilder()
            ->add(
                'searchField',
                TextType::class,
                [
                    'label' => false,
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'search',
                    'attr' =>
                        [
                            'class' => 'Link Link--edit',
                        ],
                ]
            )
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchString = $form->getData()['searchField'];
            $cribs = $cribRepository->findBySearchString($searchString);
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
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new", name="new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {
        $cribContent = new CribContent();

        $crib = new Crib();
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $crib = $cribRepository->find($id);

        return $this->render(
            'cribs/show.html.twig',
            [
                'crib' => $crib,
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Crib $crib)
    {
        $crib->setEditDate(new \DateTime('now'));

        $form = $this->createForm(CribType::class, $crib);
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id, Request $request)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $crib = $cribRepository->find($id);

        $form = $this
            ->createFormBuilder()
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Delete',
                    'attr' =>
                        [
                            'class' => 'Link Link--delete',
                        ],
                ]
            )
            ->getForm();
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
