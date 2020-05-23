<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\RoomSearch;
use App\Form\BookingType;
use App\Form\RoomSearchType;
use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking.index", methods={"GET"})
     * @param BookingRepository $bookingRepository
     * @return Response
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render ('/profile/booking/index.html.twig', [
            'bookings' => $bookingRepository->findAuthorizedBookings(1),
            'current_menu' => 'booking'

        ]);}

    /**
     * @Route("/calendar", name="booking.calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('/profile/booking/calendar.html.twig',[
            "current_menu" => "booking"
        ]);
    }

    /**
     * @Route("/book/{id_user}", name="booking.new")
     * @param Request $request
     * @return Response
     */
    public function book(Request $request, RoomRepository $roomRepository)
    {
        $search = new RoomSearch();
        $rooms = [];
        $bookingData = [];
        $form = $this->createForm(RoomSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $rooms = $roomRepository->getAvailableRooms($search);
            $bookingData['title'] = $search->getTitle();
            $bookingData['dateTo'] = $search->getDateTo()->format('Y-m-d H:i:s');
            $bookingData['dateFrom'] = $search->getDateFrom()->format('Y-m-d H:i:s');
        }

        return $this->render("profile/booking/book.html.twig", array(
            'form' => $form->createView(),
            'rooms' => $rooms,
            'bookingData'  => $bookingData
        ));
    }

    /**
     * @Route("/bookRoom/{id_user}/{id_room}/{date_in}/{date_out}/{title}/{state}", name="book.new.room")
     **/
    public function bookRoom($id_user, $id_room, $date_in, $date_out, $title, $state)
    {

        $reservation = new Booking();
        $date_start = new \DateTime( $date_in );
        $date_end = new \DateTime($date_out);
        $reservation->setBeginAt($date_start);
        $reservation->setEndAt($date_end);
        $reservation->setTitle($title);
        $reservation->setState($state);

        $user = $this
            ->getDoctrine()
            ->getRepository('App:User')
            ->find($id_user);

        $room = $this
            ->getDoctrine()
            ->getRepository('App:Room')
            ->find($id_room);

        $em = $this->getDoctrine()
            ->getManager();

        $reservation->setUser($user);
        $reservation->setRoom($room);

        $em->persist($reservation);
        $em->flush();

        if ($state == 0) {
            $this->addFlash('success', 'Votre demande de réservation a bien été effectuée.');
        } else {
            $this->addFlash('success', 'Votre réservation a bien été effectuée.');
        }

        return $this->redirectToRoute('booking.index');
        return $this->render( ['current_menu' => 'booking']);
    }

    /**
     * @Route("/{id}", name="booking.show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('/profile/booking/show.html.twig', [
            'booking' => $booking,
            'current_menu' => 'booking'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre réservation a bien été éditée.');
            return $this->redirectToRoute('booking.index');
        }

        return $this->render('/profile/booking/edit.html.twig',[
            'booking' => $booking,
            'form' => $form->createView(),
            'current_menu' => 'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="booking.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
            $this->addFlash('success', 'Votre réservation a bien été supprimée.');
        }

        return $this->redirectToRoute('booking.index');
    }
}
