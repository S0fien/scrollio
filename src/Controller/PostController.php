<?php
// src/Controller/PostController.php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\PostType;
use App\Entity\Post;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Utility;
use App\Repository\PostRepository;

/**
 * @Route("/")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findBy(array('status' => 'confirmed'), array('id' => 'desc'), 8);

        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findAll();
        $user = $this->getUser();

        return $this->render('Post/index.html.twig', array(
            'posts' => $posts,
            'datUser' => $user,
            'category' => $category,
        ));
    }
    /**
     * @Route("/c/{name}", name="post_index_cat")
     */
    public function indexCat($name)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
/*        $posts = $repository->findBy(array(
            'category' => $name,
            'status' => 'confirmed'
            ), array('date' => 'desc'), 32);     */
        $posts = $repository->findBy(array(
            'category' => $name,
            'status' => 'confirmed'
            ), array('date' => 'desc'));
        //$posts = $repository->findAll();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findAll();
        $user = $this->getUser();

        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('Post/index.html.twig', array(
            'posts' => $posts,
            'datUser' => $user,
            'category' => $category,
        ));
    }

    /**
     * @Route("/load/{id}", name="post_load", requirements={"id" = "\d+"})
     */
    public function load($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->loadMore($id);
        /*
        return $this->renderView('Post/load.html.twig', array(
            'newPosts' => $posts,
        ));
        */
        dump($id);
        dump($posts);
        return $this->render('Post/load.html.twig', array(
            'newPosts' => $posts,
        ));
    }
    /**
     * @Route("/view/{id}", name="post_view", requirements={"id" = "\d+"})
     */
    public function view($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class);
        $post = $posts->find($id);

        $comments = $em->getRepository(Comment::class);
        $comms = $comments->findBy(array("postId" => $id));

        $userConn = $this->getUser();
        $comment = new Comment();

        if($userConn) {
            $comment->setAuthor($userConn->getNickname());
        }
        else $comment->setAuthor("anonyme");
        $comment->setPostId($id);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->render('Post/view.html.twig', array(
                'post' => $post,
                'form' => $form->createView(),
                'msg' => "Commentaire ajouté !",
                'comms' => $comms
            ));
        }

        return $this->render('Post/view.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'comms' => $comms
        ));
    }
}