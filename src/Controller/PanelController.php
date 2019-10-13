<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Post;
use App\Entity\Comment;


/**
 * @Route("/")
 */
class PanelController extends AbstractController
{
    /**
     * @Route("/x-panel/", name="post_x_panel")
     */
    public function panel()
    {
        return $this->render('panel/index.html.twig', array(
        ));
    }
    /**
     * @Route("/x-panel/usr/", name="post_x_panel_usr")
     */
    public function panelUsr()
    {
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository(User::class);
        $users = $usersRepo->findAll();
        return $this->render('panel/users.html.twig', array(
            'users' => $users,
        ));
    }
    /**
     * @Route("/x-panel/pst/", name="post_x_panel_pst")
     */
    public function panelPst()
    {
        $em = $this->getDoctrine()->getManager();
        $postsRepo = $em->getRepository(Post::class);
        $posts = $postsRepo->findBy(array('status' => 'pending'));
        return $this->render('panel/posts.html.twig', array(
            'posts' => $posts,
        ));
    }
    /**
     * @Route("/x-panel/del/{type}+{id}", name="post_x_panel_delete")
     */
    public function panelDelete($type, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$type == "post" || "user") {
            if ($type == "post") {
                $postRepo = $em->getRepository(Post::class);
                $target = $postRepo->find($id);
                $em->remove($target);
                $em->flush();
                return $this->redirectToRoute('post_x_panel_pst');
            }
            if ($type == "user") {
                $userRepo = $em->getRepository(User::class);
                $target = $userRepo->find($id);
                $em->remove($target);
                $em->flush();
                return $this->redirectToRoute('post_x_panel_usr');
            }
        }
        else {
            return $this->redirectToRoute('post_x_panel');
        }
    }

    /**
     * @Route("/x-panel/pst/confirm/{id}", name="post_x_panel_post_confirm")
     */
    public function panelConfirm($id)
    {
        $em = $this->getDoctrine()->getManager();
        $postRepo = $em->getRepository(Post::class);
        $target = $postRepo->find($id);
        $target->setStatus("confirmed");
        $em->flush();
        return $this->redirectToRoute('post_x_panel_pst');
    }
}