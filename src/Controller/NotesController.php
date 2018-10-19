<?php

namespace App\Controller;


use App\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{

    /**
     * @Route("/notes/new", name="notes_new")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $note = new Note();

        $form = $this->createFormBuilder($note)
            ->add('title', TextType::class)
            ->add('date', DateType::class)
            ->add('project', TextType::class)
            ->add('language', TextType::class)
            ->add('content', TextareaType::class)
            ->add('comment', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Note'])
            ->getForm()
        ;

        $note->setEditDate(new \DateTime('now'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();

            $enitiyManager = $this->getDoctrine()->getManager();
            $enitiyManager->persist($note);
            $enitiyManager->flush();

            return $this->success($note);
        }

        return $this->render('notes/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/notes/success", name="notes_sucess")
     * @param $note
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($note)
    {
        return $this->render('notes/success.html.twig', ['note' => $note]);
    }


    /**
     * @Route("/notes/show/{id}", name="notes_show")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $note            = $notesRepository->find($id);

        return $this->render('notes/show.html.twig', ['note' => $note]);
    }


    /**
     * @Route("/notes/index/{project?}", name="notes_index")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);

        $searchForm = $this->createFormBuilder()
            ->add('search_field', TextType::class, ['label' => false])
            ->add('search', SubmitType::class, ['label' => 'search', 'attr' => ['class' => 'Button Button--search']])
            ->getForm()
        ;

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted()) {
            $searchString = $searchForm->getData()['search_field'];
            $notes        = $notesRepository->findByKeyWord($searchString);

            return $this->render(
                'notes/index.search.html.twig',
                [
                    'notes'        => $notes,
                    'searchString' => $searchString,
                ]
            );
        }

        $notes = $notesRepository->findBy([], ['date' => 'DESC']);

        return $this->render(
            'notes/index.html.twig', [
                'notes' => $notes,
                'form'  => $searchForm->createView(),
            ]
        );
    }


    /**
     * @Route("/notes/index/project/{project}", name="notes_project_index")
     * @param string $project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByProject($project)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes           = $notesRepository->findByProjectName($project);

        return $this->render(
            'notes/index.project.html.twig', [
                'notes'   => $notes,
                'project' => $project,
            ]
        );

    }


    /**
     * @Route("/notes/index/language/{language}", name="notes_language_index")
     * @param string $language
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByLanugage($language)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes           = $notesRepository->findByLanguage($language);

        return $this->render(
            'notes/index.language.html.twig', [
                'notes'    => $notes,
                'language' => $language,
            ]
        );

    }


    /**
     * @Route("/notes/index/date/{date}", name="notes_date_index")
     * @param $date
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByDate($date)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes           = $notesRepository->findByDate($date);

        return $this->render(
            'notes/index.date.html.twig', [
                'notes' => $notes,
                'date'  => $date,
            ]
        );
    }


    /**
     * @Route("/notes/update/{id}", name="notes_update")
     * @param $id
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $note            = $notesRepository->find($id);

        $form = $this->createFormBuilder($note)
            ->add('title', TextType::class)
            ->add('date', DateType::class)
            ->add('project', TextType::class)
            ->add('language', TextType::class)
            ->add('content', TextareaType::class)
            ->add('comment', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Note'])
            ->getForm()
        ;


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $note->setEditDate(new \DateTime('now'));

            $enitiyManager = $this->getDoctrine()->getManager();
            $enitiyManager->persist($note);
            $enitiyManager->flush();

            return $this->success($note);
        }

        return $this->render('notes/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/notes/delete/{id}", name="notes_delete")
     * @param $id
     * @param $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id, Request $request = null)
    {
        $notesRepository = $this->getDoctrine()->getRepository(Note::class);
        $note            = $notesRepository->find($id);

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'delete', 'attr' => ['class' => 'Button Button--danger']])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $enitiyManager = $this->getDoctrine()->getManager();
            $enitiyManager->remove($note);
            $enitiyManager->flush();

            return $this->redirectToRoute('notes_index');

        }

        return $this->render('notes/delete.html.twig', ['note' => $note, 'form' => $form->createView()]);
    }
}
