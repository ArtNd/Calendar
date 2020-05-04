<?php


namespace App\Controller\Admin;


use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRoomController extends AbstractController
{
    /**
     * @var RoomRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RoomRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/room", name="admin.room.index")
     * @return Response
     */
    public function index (): Response
    {
        $rooms = $this->repository->findAll();
        return $this->render('admin/room/index.html.twig', compact('rooms'));
    }

    /**
     * @Route("/admin/room/create", name="admin.room.new")
     * @param Request $request
     * @return Response
     */
    public function new (Request $request) :Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($room);
            $this->em->flush();
            $this->addFlash('success', 'Votre salle a bien été créée.');
            return $this->redirectToRoute('admin.room.index');
        }

        return $this->render('admin/room/new.html.twig', array(
            'room' => $room,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/room/{id}", name="admin.room.edit", methods="GET|POST")
     * @param Room $room
     * @param Request $request
     * @return Response
     */
    public function edit (Room $room, Request $request): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'Votre salle a bien été éditée.');
            return $this->redirectToRoute('admin.room.index');
        }

        return $this->render('admin/room/edit.html.twig', array(
            'room' => $room,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/room{id}", name="admin.room.delete", methods="DELETE")
     * @param Room $room
     * @param Request $request
     * @return Response
     */
    public function delete (Room $room, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->get('_token')))
        {
            $this->em->remove($room);
            $this->em->flush();
            $this->addFlash('success', 'Votre salle a bien été supprimée.');
        }
        return $this->redirectToRoute('admin.room.index');
    }
}