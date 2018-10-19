<?php

namespace App\Controller;


use App\Entity\Crib;
use App\Entity\CribContent;
use App\Form\Type\CribType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Creates new crib.
     */
    public function edit(Request $request, Crib $crib)
    {

        $form = $this->createForm(CribType::class, $crib);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'crib/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/", name="index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * The crips complete index.
     */
    public function index()
    {
        $cribRepository = $this->getDoctrine()->getRepository(Crib::class);
        $cribs          = $cribRepository->findBy([], ['date' => 'DESC']);

        return $this->render(
            'cribs/index.html.twig',
            [
                'cribs' => $cribs,
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
}
