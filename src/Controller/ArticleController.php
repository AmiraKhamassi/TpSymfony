<?php

namespace App\Controller;


use App\Entity\Article;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController{ 

    /**
     * @Route("/articleshome", name="article_show_three")
     */
    public function showThreeArticles() {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findOnlyThreeArticles();
        $total = $repository->countArticles();

        return $this->render('home.html.twig', [
            'articles' => $articles,
            'total' => $total
        ]);
    }

     /**
     * @Route("/articleshome/showall", name="article_show_all")
     */
    public function showAllArticles() {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();
    
        return $this->render('article/showall.html.twig', [
            'articles' => $articles
        ]);
    }

      /**
     * @Route("/articleshome/showone/{id}", name="article_show_one_by_id")
     */
    public function showOne($id) {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $article = $repository->find($id);    

        return $this->render('article/showone.html.twig', [
            'article' => $article
        ]);
    }

      /**
     * @Route("/articleshome/create", name="article_create")
     */
    public function create(Request $request, ObjectManager $manager)
    {   
        $article = new Article();

        $form = $this->createFormBuilder($article)
        ->add('title', TextType::class)
        ->add('smallDescription', TextType::class)
        ->add('longDescription', TextareaType::class)
        ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',

                ])
            ],
        ])
        ->add('save', SubmitType::class, ['label' => 'Create'])
        ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                echo $newFilename;
                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        'imagesRepo/',
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $article->setThumbnail('/imagesRepo/'.$newFilename);
            }

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', "L'article <strong>{$article->getTitle()}</strong> a bien été enregistré");

            return $this->redirectToRoute('article_show_one_by_id', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

}

?>