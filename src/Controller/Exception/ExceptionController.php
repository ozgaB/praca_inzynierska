<?php
namespace App\Controller\Exception;

use App\Form\Type\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 */
Class ExceptionController extends AbstractController
{
/**
 *@Route("/access_denied", name="access_denied")
 *@Template()
 */
public function accessDenied()
{
    return [

    ];
}
}
?>