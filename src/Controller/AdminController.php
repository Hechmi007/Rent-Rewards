<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
class AdminController extends AbstractController
{
    /**
 * @Route("/admin/ban-user", name="admin_ban_user", methods={"POST"})
 */
public function banUser(Request $request): RedirectResponse
{
    // Get the ID of the user to ban from the request
    $userId = $request->request->get('user_id');

    // Get the user entity by ID
    $entityManager = $this->getDoctrine()->getManager();
    $user = $entityManager->getRepository(User::class)->find($userId);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Ban or unban the user based on their current status
    if ($user->isBanned()) {
        $user->setBanned(false);
        $message = 'User has been unbanned';
    } else {
        $user->setBanned(true);
        $message = 'User has been banned';
    }

    // Update the user entity in the database
    $entityManager->persist($user);
    $entityManager->flush();

    // Display a flash message to the admin
    $this->addFlash('success', $message);

    // Redirect the admin back to the dashboard
    return $this->redirectToRoute('user_index');
}
}
