<?php

namespace App\Command;

//require "./vendor/abraham/twitteroauth/autoload.php";

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\News;
use App\Entity\Hashtags;
use Doctrine\ORM\EntityManagerInterface;

use Abraham\TwitterOAuth\TwitterOAuth;

class UpdateFeedsCommand extends Command
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // имя команды (часть после "bin/console")
            ->setName('app:update-feeds')

            // краткое описание, отображающееся при запуске "php bin/console list"
            ->setDescription('Update feed from twitter.')

            // полное описание команды, отображающееся при запуске команды
            // с опцией "--help"
            ->setHelp('This command update feeds from twitter BBC')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumerKey = 'taTWgJe9JVBU9XXFu7zAK1JFX';
        $consumerKeySecret = 'EeyNv6jb36q0SWUNRpeJLFMS3pOqqzs24ZvuGki1foijgoG49W';

        $accessToken = '97639832-Z5o8XZi8dcUTpeUBfpcndD7MOnxT7iwkI7QeHJlDm';
        $accessTokenSecret = 'GzFlBECCLKx8xSK4kkfZguYFHGvSsZEPWIBTn1kEcgIXr';

        $connection = new TwitterOAuth($consumerKey, $consumerKeySecret, $accessToken, $accessTokenSecret);

        $tweets = $connection->get("statuses/user_timeline", ["count" => 20,"screen_name" => "BBC", "exclude_replies" => true,"include_entities" => "true","tweet_mode"=>"extended"]);

        $em = $this->entityManager;

        $repository = $em->getRepository(News::class);

        foreach($tweets as $tweet)
        {
            $newsId = $this->CreateNews($tweet, $em, $repository);

            if($newsId == 0)
            {
                break;
                return;
            }

            $tags = array();

            foreach($tweet->entities->hashtags as $tag)
            {
                array_push($tags, $tag->text);
            }

            $this->CreateTag($em, $tags, $newsId);
        }

        

        
    }

    // Добавление или обновление тэгов в БД
    public function CreateTag($em, $tags, $newsId)
    {
        $tagsRepository = $em->getRepository(Hashtags::class);

            foreach($tags as $tag)
            {
                $findTag = $tagsRepository->findOneBy(['name' => $tag]);

                if(count($findTag) == 0)
                {
                    $newTag = new Hashtags();
                    $newTag->setName($tag);

                    $feedsIds = array();
                    array_push($feedsIds, $newsId);

                    $newTag->setFeeds(json_encode($feedsIds));

                    
                    $newTag->setViews('0');

                    $em->persist($newTag);
                    $em->flush();

                }
                else
                {
                    $feedsList = $findTag->getFeeds();
                    $feedsList = json_decode($feedsList);

                    array_push($feedsList, $newsId);

                    $feedsList = json_encode($feedsList);

                    $findTag->setFeeds($feedsList);

                    $em->persist($findTag);
                    $em->flush();

                }
            }
    }

    // Формирование новости и добавление записи в БД + проверка на актуальность
    public function CreateNews($tweet, $em, $repository)
    {
        $timestamp = strtotime($tweet->created_at);

            $tags = array();

            foreach($tweet->entities->hashtags as $tag)
            {
                array_push($tags, $tag->text);
            }

            
            //print_r($tweet->entities->media[0]->media_url);

            $product = $repository->findOneBy(['time_hash' => $timestamp]);

            if(count($product) > 0)
            {
                print("Not found new tweets!");
                return 0;
            }

            $news = new News();
            
            $text = "";

            if(!isset($tweet->text))
            {
                $text = $tweet->full_text;
            }
            else
            {
                $text = $tweet->text;
            }

            $formatTitle = $this->FormatHashtags($tags, $text);

            $news->setTitle($formatTitle);
            $news->setDateTime($tweet->created_at);
            $news->setTimeHash($timestamp);
            $news->setText($text);
            $news->setTags(json_encode($tags));

            if(count($tweet->entities->media) > 0)
            {
                $news->setImage($tweet->entities->media[0]->media_url);
            }
            else
            {
                $news->setImage("NULL");
            }

            $em->persist($news);
            $em->flush();

            $newsId = $news->getId();

            return $newsId;
    }


    // Форматирование текста под кликабельность тэгов
    public function FormatHashtags($tags, $title)
    {
        $newTitle = $title;
        foreach($tags as $tag)
        {
            $tagLink = '<a href="/tags/'.$tag.'">#'.$tag.'</a>';
            $newTitle = str_replace("#".$tag, $tagLink, $newTitle);
        }

        return $newTitle;
    }


}