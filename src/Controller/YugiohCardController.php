<?php
namespace App\Controller;

use App\Entity\Card;
use App\Entity\CardAttribute;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\ORMException;

class YugiohCardController extends AbstractController
{
    /**
     * @Route("/card/overview")
     */
    public function YugiohCardOverview(): Response
    {
        //Get all cards from the table Card
        $registeredCards = $this->getDoctrine()->getRepository(Card::class)->findAll();

        //return view for overview of the cards with the data from the database
        return $this->render('cardOverview.html.twig', [
            'registeredCards' => $registeredCards,
        ]);
    }

    /**
     * @Route("/card/detail/{id}", methods={"GET"})
     */
    public function YugiohCardDetail(int $id): Response
    {
        //check if id is an int
        if(is_int($id))
        {
            //get data from card table with specific id
            $card = $this->getDoctrine()->getRepository(Card::class)->find($id);

            //if the result is not null render the detail page
            if($card !== null)
            {
                return $this->render('cardDetails.html.twig', [
                    'card' => $card
                ]); 
            }
            else
            {
                $response = new Response();
                $response->setContent('No matching ID found');
                return $response;
            }       
            
        }
        else
        {
            $response = new Response();
            $response->setContent('Invalid ID');
            return $response;
        }
    }

    /**
     * @Route("/card/addForm")
     */
    public function YugiohCardAddForm() : Response
    {
        //return view with the form for adding a card
        return $this->render('cardAddForm.html.twig', []);
    }

    /**
     * @Route("/cardAdd/post", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function YugiohCardAddPost(Request $request, EntityManagerInterface $entityManager) : Response
    {
        //get form data from the request
        $cardInformation = $request->request;
        
        //create new card object and add the data from to the form
        $newCard = new Card();
        $newCard->setCardName($cardInformation->get('cardName'));
        $newCard->setType($cardInformation->get('cardType'));
        $newCard->setAttackValue($cardInformation->get('cardAttackValue'));
        $newCard->setDefenceValue($cardInformation->get('cardDefenceValue'));
        $newCard->setCardDescription($cardInformation->get('cardDescription'));
        $newCard->setCollectionID($cardInformation->get('cardCollectionID'));

        $attribute = $this->getDoctrine()->getRepository(cardAttribute::class)->find($cardInformation->get('cardAttribute'));

        $newCard->setAttribute($attribute);

        //validate the information
        if(!$this->isNewCardInformationValid($newCard)){
            $response = new Response();
            $response->setContent('Card information is invalid');
            return $response;
        }

        //get the uploaded file and check if the file is really and image
        $uploadedImage = $request->files->get('cardImage');
        if($this->isUploadedFileAnImage($uploadedImage))
        {
            //clean the image name and save it in public/uploads
            $originalImageName = pathinfo($uploadedImage->getClientOriginalName(), PATHINFO_FILENAME);
            $slugger = new AsciiSlugger();
            $safeImageName = $slugger->slug($originalImageName);
            $newImagename = $safeImageName.'-'.uniqid().'.'.$uploadedImage->guessExtension();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

            $uploadedImage->move(
                $destination,
                $newImagename
            );

            $newCard->setImgPath($newImagename);
        }
        else
        {
            $response = new Response();
            $response->setContent('uploaded file was not an image');
            return $response;
        }

        //Insert new card data into the card table
        try {
            $entityManager->persist($newCard);
            $entityManager->flush();
            return($this->redirect('/card/overview'));
        }
        catch(ORMException $e) {
            //create eresponse with the message that an database error has occured
            $response = new Respone();
            $response->setContent('Database error');
            $response->isInvalid();
            return $response;
        }
        
    }

    //card information validator
    private function isNewCardInformationValid(Card $newCard) : bool
    {
        if(is_string($newCard->getCardName()) &&
           is_string($newCard->getType()) &&
           is_string($newCard->getAttackValue()) &&
           is_string($newCard->getDefenceValue()) &&
           is_string($newCard->getCollectionID()) &&
           is_string($newCard->getCardDescription()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //uploaded file validator checks if file is an image
    private function isUploadedFileAnImage(object $uploadedImage) : bool
    {
        $imageTypes = array ('image/bmp',
                        'image/gif',
                        'image/x-freehand',
                        'image/x-freehand',
                        'image/x-freehand',
                        'image/x-icon',
                        'image/jpeg',
                        'image/jpeg',
                        'image/jpeg',
                        'image/png',
                        'image/tiff',
                        'image/tiff');

        if(in_array($uploadedImage->getMimeType(), $imageTypes))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
