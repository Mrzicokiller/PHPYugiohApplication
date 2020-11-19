<?php
// src/Controller/LuckyController.php
namespace App\Controller;

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
        return $this->render('cardOverview.html.twig', []);
    }
}
?>
