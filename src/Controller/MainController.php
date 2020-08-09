<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\News;
use App\Entity\Hashtags;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("result", name="result", methods={"POST"})
     */
    public function search(Request $request)
    {
        $findText = (string)$request->get('search')['text'];
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(News::class);

        $news = $repository->findLike($findText);

        $newsList = array();
        
        foreach($news as $post)
        {
            $newsBlock = new News();
            $newsBlock->setImage($post['image']);
            $newsBlock->setTitle($post['title']);
            $newsBlock->setDateTime($post['date_time']);

            array_push($newsList, $newsBlock);
        }

        return $this->render('main.html.twig',[
            'news' => $newsList,
        ]);
    }

    /**
     * @Route("index")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(News::class);

        

        $news = $repository->getOrderNews();

        $newsList = array();
        
        foreach($news as $post)
        {
            $newsBlock = new News();
            $newsBlock->setImage($post['image']);
            $newsBlock->setTitle($post['title']);
            $newsBlock->setDateTime($post['date_time']);

            array_push($newsList, $newsBlock);
        }

        return $this->render('main.html.twig',[
            'news' => $newsList,
        ]);
    }
}