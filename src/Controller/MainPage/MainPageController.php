<?php
namespace App\Controller\MainPage;

use App\Form\Type\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Repository\MainPageRepository;

/**
 * Class MainPageController
 */
Class MainPageController extends AbstractController
{
/**
 *@Route("/", name="home_page")
 *@Template()
 */
public function index(MainPageRepository $pageRepository)
{
    $pageElementArray = $pageRepository->getAllFromPageElement();
    if(null !== $pageElementArray)
    {
        $pageElement = $pageRepository->findOneBy(['id' => $pageElementArray['id']]);
        
        return [
            'firstText' => $pageElement->getFirstText(),
            'secondText' => $pageElement->getFirstText(),
            'trainerSectionVisible' => $pageElement->getTrainerSectionVisible(),
        ];
    }
    return [

    ];
}
}
?>
