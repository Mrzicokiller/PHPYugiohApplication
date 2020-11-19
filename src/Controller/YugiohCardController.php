<?php
namespace App\Controller;

use App\Entity\Card;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
?>
