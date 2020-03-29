<?php

namespace App\Controller;

use App\Entity\Article;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articleList")
     */
    public function index()
    {
        /*return new Response(
            '<html><body>Hi there</body></html>'
        );*/
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig',
            [
                'articles' => $articles
            ]);
    }


    /**
     * @Route("/article/new", name="articleNew" ,methods={"GET", "POST"})
     */
    public function articleNew(Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class,
                ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class,
                ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class,
                ['label' => 'Create', 'attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articleList');
        }

        return $this->render('articles/articleNew.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/edit/{id}", name="articleEdit" ,methods={"GET", "POST"})
     */
    public function articleEdit(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class,
                ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class,
                ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class,
                ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('articleList');
        }

        return $this->render('articles/articleEdit.html.twig',
            ['form' => $form->createView()]);
    }


    /**
     * @Route("/article/delete/{id}", name="articleDelete", methods={"DELETE"})
     */
    public function articleDelete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/article/{id}", name="ArticleShow")
     */
    public function articleShow($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/articleShow.html.twig',
            ['article' => $article]);
    }


    /**
     * @Route("/article/save")
     * @return Response
     *//*
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle('Article One');
        $article->setBody('This is the body of article one');

        $entityManager->persist($article);

        $entityManager->flush();

        return new Response('Saved an article with the article with the id of ' . $article->getId());
    }*/
}