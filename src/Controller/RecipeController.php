<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * List all recipes
     *
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'app_recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findAll(),
            $request->query->getInt('page', 1)
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recette/create', 'app_recipe_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', 'Votre recette a été créée avec succès !');
            return $this->redirectToRoute('app_recipe');
        }
        return $this->render('pages/recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recette/edit/{id}', 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(RecipeRepository $recipeRepository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', 'Votre recette a été avec succès !');
            return $this->redirectToRoute('app_recipe');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recette/delete/{id}', 'app_recipe_delete', methods: ['GET'])]
    public function delete(RecipeRepository $recipeRepository, int $id, EntityManagerInterface $manager): Response
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        if (!$recipe) {
            $this->addFlash('success', 'Votre recette n\'a pas été trouvée !');
            return $this->redirectToRoute('app_recipe');
        }
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash('success', 'Votre recette a été supprimée avec succès !');
        return $this->redirectToRoute('app_recipe');
    }
}
