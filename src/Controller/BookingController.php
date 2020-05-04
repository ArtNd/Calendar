<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
        return $this->render('/profile/booking/index.html.twig', [
            'bookings' => $bookingRepository->findAuthorizedBookings(1),
        ]);
    }

    /**
     * @Route("/calendar", name="booking.calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('/profile/booking/calendar.html.twig');
    }

    /**
     * @Route("/book/{id_user}", name="booking.new")
     * @param Request $request
     * @param $id_user
     * @return Response
     */
    public function book(Request $request, $id_user)
    {
        $data = [];
        $data['id_user'] = $id_user;

        $data['rooms'] = null;
        $data['dates']['from'] = '';
        $data['dates']['to'] = '';
        $data['title'] = '';
        $form = $this  ->createFormBuilder()
            ->add('dateFrom')
            ->add('dateTo')
            ->add('title')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();

            $data['dates']['from'] = $form_data['dateFrom'];
            $data['dates']['to'] = $form_data['dateTo'];
            $data['title'] = $form_data['title'];

            $em = $this->getDoctrine()->getManager();
            $rooms = $em->getRepository('App:Room')
                ->getAvailableRooms($form_data['dateFrom'], $form_data['dateTo']);

            $data['rooms'] = $rooms;

        }

        $user = $this
            ->getDoctrine()
            ->getRepository('App:User')
            ->find($id_user);

        $data['user'] = $user;

        return $this->render("profile/booking/book.html.twig", $data);
    }

    /**
     * @Route("/bookRoom/{id_user}/{id_room}/{date_in}/{date_out}/{title}/{state}", name="book.new.room")
     **/
    public function bookRoom($id_user, $id_room, $date_in, $date_out, $title, $state)
    {

        $reservation = new Booking();
        $date_start = new \DateTime( $date_in );
        $date_end = new \DateTime($date_out);
        $reservation->setBeginAt( $date_start );
        $reservation->setEndAt( $date_end );
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

    }

    /**
     * @Route("/{id}", name="booking.show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('/profile/booking/show.html.twig', [
            'booking' => $booking,
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

        return $this->render('/profile/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
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
