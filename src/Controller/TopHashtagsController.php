<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Hashtags;

class TopHashtagsController extends AbstractController
{
    
    public function main()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $tagsRepository = $entityManager->getRepository(Hashtags::class);

        $topTags = $tagsRepository->findTopTags();



        return $this->render('top_hashtags/index.html.twig', [
            'topTags' => $topTags,
        ]);
    }
}
