<?php

namespace App\Controller;


use App\Entity\Crib;
use App\Entity\CribContent;
use App\Form\Type\CribType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CribController extends AbstractController
{

    /**
     * @Route("/new", name="new")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Creates new crib.
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
            'cribs/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     *
     * @param \App\Entity\Crib                          $crib
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Creates new crib.
     */
    public function edit(Request $request, Crib $crib)
    {
        $form = $this->createForm(CribType::class, $crib);

        $crib->setEditDate(new \DateTime('now'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'cribs/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/{field}-{direction}", name="index")
     * @param $field
     *
     * @param $direction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($field = null, $direction = null, Request $request)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);

        $searchForm =
            $this
                ->createFormBuilder()
                ->add('searchField', TextType::class, ['label' => false])
                ->add('submit', SubmitType::class, ['label' => 'search'])
                ->getForm()
        ;

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted()) {
            $searchString = $searchForm->getData()['searchField'];
            $cribs        = $cribRepository->findBySearchString($searchString);
        } else {
            $cribs = $cribRepository->findBy(
                [],
                [
                    $field ? $field : 'date' => $direction ? $direction : 'DESC',
                ]
            );
        }

        return $this->render(
            'cribs/index.html.twig',
            [
                'cribs'      => $cribs,
                'searchForm' => $searchForm->createView(),
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
     * @Route("/delete/{id}", name="delete")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $crib           = $cribRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($crib);
        $em->flush();

        return $this->redirectToRoute('index');
    }
}
