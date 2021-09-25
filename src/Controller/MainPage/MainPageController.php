<?php
namespace App\Controller\MainPage;

use App\Form\Type\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class MainPageController
 */
Class MainPageController extends AbstractController
{
/**
 *@Route("/", name="home_page")
 *@Template()
 */
public function index()
{
    return [

    ];
}
}
?>
