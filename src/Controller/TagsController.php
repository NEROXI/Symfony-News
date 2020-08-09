<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Hashtags;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class TagsController extends AbstractController
{
    /**
     * @Route("/tags", methods={"GET"})
     */
    public function tags()
    {   
        return $this->redirect('/');
    }
    /**
     * @Route("/tags/{tag}", methods={"GET"})
     */
    public function index(string $tag)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $repository = $entityManager->getRepository(Hashtags::class);

        $newsList = $repository->findOneBy(['name' => $tag]);


        $newsByTagList = array();

        if(count($newsList) > 0)
        {

            $newsList->setViews($newsList->getViews() + 1);

            $entityManager->persist($newsList);
            $entityManager->flush();

            $newsIdsList = json_decode($newsList->getFeeds());

            $repository = $entityManager->getRepository(News::class);

            foreach($newsIdsList as $newsId)
            {
                $feed = $repository->findOneBy(['id' => $newsId]);
                array_push($newsByTagList, $feed);
            }
        }

        return $this->render('tags/index.html.twig', [
            'tag' => $tag,
            'news' => $newsByTagList,
        ]);
    }
}
