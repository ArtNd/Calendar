<?php

namespace App\Controller\Manager;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagerBookingController extends AbstractController
{
    /**
     * @Route("/manager", name="manager.booking.index")
     * @param BookingRepository $repository
     * @return Response
     */
    public function index(BookingRepository $repository)
    {
        return $this->render('manager/index.html.twig', array(
            'bookings' => $repository->findAuthorizedBookings(0)
        ));
    }

    /**
     * @Route("/manager/{id}", name="manager.acceptBooking")
     * @param Booking $booking
     * @param BookingRepository $repository
     * @return Response
     */
    public function acceptBooking(Booking $booking, BookingRepository $repository): Response
    {
        $booking->setState(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();
        $this->addFlash('success', 'Votre réservation a bien été acceptée.');

        return $this->render('manager/index.html.twig', array(
            'bookings' => $repository->findAuthorizedBookings(0)
        ));
    }

    /**
     * @Route("manager/{id}", name="manager.booking.refuse", methods={"DELETE"})
     * @param Request $request
     * @param Booking $booking
     * @return Response
     */
    public function refuse(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('refuse'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
            $this->addFlash('success', 'Votre réservation a bien été refusée.');
        }

        return $this->redirectToRoute('manager.booking.index');
    }
}