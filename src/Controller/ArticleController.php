<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


// i can do a main route like Route("/article", name"article.") it's just going to add all the names of the routes with a ., it just looks cooler
class ArticleController extends Controller
{

    /**
     * @Route("/", name="list_article", methods={"GET"})
     */
    public function index()
    {
        //return new Response('<h1>here<h1>');
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    
    /**
     * @Route("/article/edit/{id}", name="edit_article", methods={"GET","POST"})
     */
    public function edit(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                    ]
                    ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                    ]
                    ])
            ->add('save', SubmitType::class, [
                'label' => 'Edit',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                    ]
                    ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('list_article');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/article/new", name="new_article", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                    ]
                    ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                    ]
                    ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                    ]
                    ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('list_article');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/article/delete/{id}", methods={"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/article/{id}", name="show_article", methods={"GET"})
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('articles/show.html.twig', ['article' => $article]);
    }


    // /**
    // * @Route("/article/save")
    // */
    // public function save(){
    //     $entiryManager = $this->getDoctrine()->getManager();

    //     $article = new Article();
    //     $article->setTitle((string) "Article One");
    //     $article->setBody("This is the body of the first article");

    //     $entiryManager->persist($article);
    //     $entiryManager->flush();

    //     return new Response("Saved an article with the id of ". $article->getId());
    // }


}
