<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private MovieRepository $movieRepository;
    private EntityManagerInterface $em;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/movies', name: 'movie_list', methods: ['GET'])]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();

        return $this->render('movies/index.html.twig', compact('movies'));
    }

    /**
     * oldMethod
     *
     * @Route("/old", name="old")
     */
    public function oldMethod(): Response
    {
        return $this->json([
            'message' => 'Old method',
            'path' => 'src/Controller/MovieController.php',
        ]);
    }

    #[Route('/movies/create', name: 'movie_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $movie = new Movie();

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();

            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newMovie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('movie_list');
        }

        return $this->renderForm('movies/create.html.twig', ['form' => $form]);
    }

    #[Route('/movies/{id}', name: 'movies_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $movie = $this->movieRepository->find($id);

        return $this->render('movies/show.html.twig', compact('movie'));
    }

    #[Route('/movies/edit/{id}', name: 'movie_edit', methods: ['GET', 'POST'])]
    public function ediit(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $oldImagePath = $movie->getImagePath();

                $movie->setImagePath('/uploads/' . $newFileName);

                $this->em->flush();

                @unlink($this->getParameter('kernel.project_dir') . '/public' . $oldImagePath);

                return $this->redirectToRoute('movie_list');
            } else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());

                $this->em->flush();

                return $this->redirectToRoute('movie_list');
            }
        }

        return $this->renderForm('movies/edit.html.twig', ['form' => $form]);
    }

    #[Route('/movies/delete/{id}', name:'movie_delete', methods:['GET', 'DELETE'])]
    public function delete(int $id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('movie_list');
    }
}
