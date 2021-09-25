<?php
namespace App\Controller\Security;

use App\Utils\CRUD\UserCRUD;
use App\Entity\Security\User;
use App\Form\Type\RegisterUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * 
     * @Template()
     */
    public function register(Request $request, UserCRUD $userCrud)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if($userCrud->registerUser($form))
        {
            return $this->redirectToRoute('home_page');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/login", name="login")
     * 
     * @Template()
     */
    public function login(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'error'         => $error,
        ];
    }

    /**
     * @Route("/login_success", name="login_success")
     */
    public function postLoginRedirectAction(Request $request, Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();
        $userRoles = $user->getRoles();
        
        if (in_array("ROLE_USER",$userRoles)) {
            return $this->redirectToRoute("user_home_page");
        } else {
            return $this->redirectToRoute("trainer_home_page");
        }
    }

    /**
     * @Route("/logout", name="logout" , methods={"GET"})
     */
    public function logout(){

        return $this->redirectToRoute('home_page');
    }

}
?>
