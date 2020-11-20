<?php
namespace App\Controller;

use App\Entity\Card;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class YugiohCardController extends AbstractController
{
    /**
     * @Route("/card/overview")
     */
    public function YugiohCardOverview(): Response
    {
        //Get all cards from the table
        $registeredCards = $this->getDoctrine()->getRepository(Card::class)->findAll();

        //return view for overview of the cards with the data from the database
        return $this->render('cardOverview.html.twig', [
            'registeredCards' => $registeredCards
        ]);
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
     * @param LoggerInterface $logger
     * @return Response
     */
    public function YugiohCardAddPost(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger) : Response
    {
        //get form data from the request
        $cardInformation = $request->request;
        
        //create new card object and add the data from to the form
        $newCard = new Card();
        $newCard->setCardName($cardInformation->get('cardName'));
        $newCard->setType($cardInformation->get('cardType'));
        $newCard->setAttribute($cardInformation->get('cardAttribute'));
        $newCard->setAttackValue($cardInformation->get('cardAttackValue'));
        $newCard->setDefenceValue($cardInformation->get('cardDefenceValue'));
        $newCard->setCardDescription($cardInformation->get('cardDescription'));
        $newCard->setCollectionID($cardInformation->get('cardCollectionID'));
        if(!$this->isNewCardInformationValid($newCard)){
            $response = new Response();
            $response->setContent('Card information is invalid');
            return $response;
        }

        $uploadedImage = $request->files->get('cardImage');
        if($this->isUploadedFileAnImage($uploadedImage))
        {
            $originalImageName = pathinfo($uploadedImage->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
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

    private function isNewCardInformationValid(Card $newCard) : bool
    {
        if(is_string($newCard->getCardName()) &&
           is_string($newCard->getType()) &&
           is_string($newCard->getAttribute()) &&
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
