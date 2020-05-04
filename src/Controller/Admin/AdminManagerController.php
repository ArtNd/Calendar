<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\AdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminManagerController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/manager", name="admin.manager.index")
     * @return Response
     */
    public function index(): Response
    {
        $users = $this->repository->findAll();
        return $this->render('admin/manager/index.html.twig', compact('users'));
    }

    /**
     * @Route("/admin/manager/{id}", name="admin.manager.edit")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request) :Response
    {
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'Votre utilisateur a bien été édité.');
            return $this->redirectToRoute('admin.manager.index');
        }
        return $this->render('admin/manager/edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }
}