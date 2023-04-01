<?php
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
// ...

class RegistrationController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;
    
    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }
    
    /**
     * @Route("/register", name="register-user")
     */
    public function register(): Response
    {
        $user = new User();
    
        // handle the user registration form and persist the new user...
    
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                $user->getId(),
                $user->getEmail(),
            ['id' => $user->getId()] // add the user's id as an extra query param
            );
        
        $email = new TemplatedEmail();
        $email->from('send@example.com');
        $email->to($user->getEmail());
        $email->htmlTemplate('registration/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
        
        $this->mailer->send($email);
    
        // generate and return a response for the browser
    }

    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response 
    {
        
       $id = $request->get('id'); // retrieve the user id from the url

       // Verify the user id exists and is not null
       if (null === $id) {
           return $this->redirectToRoute('app_home');
       }

       $user = $userRepository->find($id);

       // Ensure the user exists in persistence
       if (null === $user) {
           return $this->redirectToRoute('app_home');
       }

        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
        // ...
    }
    }}