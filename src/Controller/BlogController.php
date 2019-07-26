<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Post;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController{
    
   
    
    /**
     * @Route("/add", name="add-post", methods={"POST"})
     */
    public function add(Request $request){
        
        $serializer = $this->get('serializer');
        $post = $serializer->deserialize($request->getContent(), Post::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json($post);
    }


    /**
     * @Route("/{page}", defaults={"page": 5}, name="get-all-posts", methods={"GET"})
     */
    public function index($page, Request $request){

        $repository = $this->getDoctrine()->getRepository(Post::class);

        $posts = $repository->findAll();

        return  $this->json([
            'page' => $page,
            'limit' => $request->get('limit', 10),
            'data' => array_map(function(Post $post){
                return [
                    "title" => $post->getTitle(),
                    "content" => $post->getContent(),
                    "user" => $post->getAuthor(),
                    "link" => $this->generateUrl('get-one-post-by-id', ['id' => $post->getId()])
                ];

            }, $posts)
        ]);
    }

    /**
     * @Route("/post/{id}", requirements={"id": "\d+"}, name="get-one-post-by-id", methods={"GET"})
     * @ParamConverter("post", class="App:Post")
     */
    public function postById($post){

        /* $repository = $this->getDoctrine()->getRepository(Post::class);

        $post = $repository->findBySlug($slug);*/

        return  $this->json($post);
    }
    
    /**
     * @Route("/post/{slug}", name="get-one-post-by-slug", methods={"GET"})
     * @ParamConverter("post", class="App:Post", options={"mapping": {"slug" : "slug"}})
     */
    public function postBySlug($post){
        
        /* $repository = $this->getDoctrine()->getRepository(Post::class);

        $post = $repository->findBySlug($slug).*/

        return  $this->json($post);
    }

    
    /**
     * @Route("/post/{id}", name="delete-post", methods={"DELETE"})
     */
    public function destroy(Post $post){

        $em = $this->getDoctrine()->getManager();

        $em->remove($post);

        $em->flush();

        return $this->json(null, 204);

    }

}

 ?>