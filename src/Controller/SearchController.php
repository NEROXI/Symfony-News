<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index()
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search, 
        [
            'action' => $this->generateUrl('result'),
            'method' => 'POST'
        ]);
        


        return $this->render('search/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
