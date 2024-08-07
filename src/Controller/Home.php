<?php
namespace App\Controller;

use App\Entity\Idea;
use App\Entity\Role;
use App\Repository\AccountRepository;
use App\Repository\IdeaRepository;
use App\Service\Functions;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Home extends AbstractController
{
    private IdeaRepository $ideaRepository;
    private AccountRepository $accountRepository;

    public function __construct(IdeaRepository $ideaRepository, AccountRepository $accountRepository)
    {
        $this->ideaRepository = $ideaRepository;
        $this->accountRepository = $accountRepository;
    }

    #[Route('/')]
    #[Route('/home')]
    public function home(Request $request)
    {
        Functions::checkUserSession($this->accountRepository);

        $_ideas = $this->ideaRepository->findAll();

        $ideas = [];
        foreach ($_ideas as $idea) {
            $ideas[] = [
                'title_idea' => $idea->getTitle(),
                'details_idea' => $idea->getDetails(),
                'choice_mesures' => $idea->getChoiceMesures(),
                'details_mesures' => $idea->getDetailsMesures(),
                'choice_funding' => $idea->getChoiceFunding(),
                'funding_details' => $idea->getDetailsFunding(),
                'team' => $idea->getTeam(),
                'validator_id' => $idea->getValidator() != null ? $idea->getValidator()->getId() : "",
                'validator_givenname' => $idea->getValidator() != null ? $idea->getValidator()->getGivenName() : "",
                'validator_familyname' => $idea->getValidator() != null ? $idea->getValidator()->getFamilyName() : "",
                'author_id' => $idea->getAuthor()->getId(),
                'idea_id' => $idea->getId(),
                'first_name' => $idea->getAuthor()->getGivenName(),
                'family_name' => $idea->getAuthor()->getFamilyName(),
                'creationDateTime' => $idea->getCreationDateTime(),
                'state_idea' => $idea->getState(),
            ];
        }

        // dd($_SESSION['role']);
        // dd(in_array('admin',$_SESSION['role']) ? 'true' : 'false');
        $data = [
            'given_name' => $_SESSION['given_name'],
            'family_name' => $_SESSION['family_name'],
            'user_id' => $_SESSION['account_id'],
            'is_admin' => in_array('admin', $_SESSION['role']) ? 'true' : 'false',
            'ideas' => $ideas,
        ];
        return $this->render('home.html.twig', $data);
    }

    #[Route('/error403')]
    public function error403()
    {
        return $this->render('errors/error403.html');
    }

    #[Route('/error404')]
    public function error404()
    {
        return $this->render('errors/error404.html');
    }

    #[Route('/ideas/{state}')]
    public function allIdeas($state) 
    {
        Functions::checkUserSession($this->accountRepository);

        $_ideas = $this->ideaRepository->findAll();

        $ideas = [];
        foreach ($_ideas as $idea) {
            
            if($idea->getState() == $state) {
                $ideas[] = array(
                    'title_idea' => $idea->getTitle(),
                    'details_idea' => $idea->getDetails(),
                    'choice_mesures' => $idea->getChoiceMesures(),
                    'details_mesures' => $idea->getDetailsMesures(),
                    'choice_funding' => $idea->getChoiceFunding(),
                    'funding_details' => $idea->getDetailsFunding(),
                    'team' => $idea->getTeam(),
                    'validator_id' => $idea->getValidator() != null ? $idea->getValidator()->getId() : "",
                    'validator_givenname' => $idea->getValidator() != null ? $idea->getValidator()->getGivenName() : "",
                    'validator_familyname' => $idea->getValidator() != null ? $idea->getValidator()->getFamilyName() : "",
                    'author_id' => $idea->getAuthor()->getId(),
                    'idea_id' => $idea->getId(),
                    'first_name' => $idea->getAuthor()->getGivenName(),
                    'family_name' => $idea->getAuthor()->getFamilyName(),
                    'creationDateTime' => $idea->getCreationDateTime(),
                    'state_idea' => $idea->getState(),
                    'is_archived' => $idea->isArchived() ? 'true' : 'false',
                );
            }
        }
                
        $data = [
            'ideas' => $ideas,
            'is_admin' => in_array('admin', $_SESSION['role']) ? 'true' : 'false',
        ];

        return $this->render('/idea/all_ideas.html.twig', $data);
    }
}
