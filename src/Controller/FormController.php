<?php
// src/Controller/FormController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;
use App\Form\SignupType;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


class FormController extends AbstractController
{
    /**
     * @Route("/signup", name="signup_form")
     */
    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthorizationCheckerInterface $authChecker)
    {
        $userConn = $this->getUser();
        if($userConn) {
            return $this->redirectToRoute('post_index');
        }
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles[] = 'ROLE_USER';
            $user->setRoles($roles);
            $pwd = $user->getPassword();
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $pwd
            ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->render('default/signup.html.twig', array(
                'form' => $form->createView(),
                'msg' => "Inscription validée !",
            ));
        }

        return $this->render('default/signup.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/new/{type}", name="add_form")
     */
    public function new(Request $request, UploaderHelper $helper, $type)
    {
        $article = new Post();
        $form = $this->createForm(PostType::class, $article);

        if ($type != "video" && $type != "photo") {
            return $this->redirectToRoute("post_index");
        }
        if ($type == "photo") {
        $article->setType("photo");
        }
        else if ($type == "video") {
            $article->setType("video");
            $form->remove("imageFile");
            $form->add("content", TextType::class);
        }
        $user = $this->getUser();
        $article->setAuthor($user->getNickname());
        $article->setAuthor_Id($user->getId());

        $repository = $this->getDoctrine()->getRepository(Category::class);
        $cat = $repository->findAll();
        $form->add("category", ChoiceType::class, [
        'choices' => [
            $cat[0]->getName() => $cat[0]->getName(),
            $cat[1]->getName() => $cat[1]->getName(),
            $cat[2]->getName() => $cat[2]->getName(),
            $cat[3]->getName() => $cat[3]->getName(),
        ],]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            //$em->flush();
            //GETTING FILE PATH
            if ($article->getType() == "photo") {
                $path = $helper->asset($article,'imageFile');
                //$img = $article->getImage()->getName();
                $article->setContent($path);
            }
            $em->persist($article);
            $em->flush();
        }
        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
            'type' => $type,
        ));
    }
    /**
     * @Route("/edit/{id}", name="edit_form", requirements={"id" = "\d+"})
     */
    public function edit(Request $request, $id)
    {
        //return $this->redirectToRoute('post_index');

        $repository = $this->getDoctrine()->getRepository(Post::class);
        $articleTest = $repository->find($id);
        $type = $articleTest->getType();

        $form = $this->createForm(PostType::class, $articleTest);

        if ($type != "video" && $type != "photo") {
            return $this->redirectToRoute("post_index");
        }
        if ($type == "photo") {
            $articleTest->setType("photo");
        }
        else if ($type == "video") {
            $articleTest->setType("video");
            $form->remove("imageFile");
            $form->add("content", TextType::class);
        }

        $repositoryCat = $this->getDoctrine()->getRepository(Category::class);
        $cat = $repositoryCat->findAll();
        $form->add("category", ChoiceType::class, [
            'choices' => [
                $cat[0]->getName() => $cat[0]->getName(),
                $cat[1]->getName() => $cat[1]->getName(),
                $cat[2]->getName() => $cat[2]->getName(),
                $cat[3]->getName() => $cat[3]->getName(),
            ],]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            //$this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
            'articleTest' => $articleTest,
            'type' => $type,
        ));
    }
}