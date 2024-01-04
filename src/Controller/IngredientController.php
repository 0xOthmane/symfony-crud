<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * This function display all ingredients
     *
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(),
            $request->query->getInt('page', 1)
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This controller show the form to add an Ingredient in database
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/create', 'app_ingredient_create', methods: ['GET', 'POST'])]
    public function createIngredient(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $ingredient = $form->getData();
                $manager->persist($ingredient);
                $manager->flush();
                $this->addFlash('success', 'Votre ingrédient a été créé avec succès');
                return $this->redirectToRoute('app_ingredient');
            }
        }
        return $this->render('pages/ingredient/createIngredient.html.twig', [
            'form' => $form,

        ]);
    }

    #[Route('/ingredient/update/{id}', 'app_ingredient_update', methods: ['GET', 'POST'])]
    public function update(IngredientRepository $ingredientRepository, int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = $ingredientRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingrédient a été modifié avec succés!');
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/update.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/ingredient/delete/{id}', 'app_ingredient_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, int $id, IngredientRepository $ingredientRepository): Response
    {
        $ingredient = $ingredientRepository->findOneBy(['id'=>$id]);
        if (!$ingredient){
            $this->addFlash('success', 'Votre ingrédient n\'a pas été trouvé !');
            return $this->redirectToRoute('app_ingredient');
        }
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash('success', 'Votre ingrédient a été supprimé avec succès !');

        return $this->redirectToRoute('app_ingredient');
    }
}
