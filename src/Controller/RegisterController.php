<?php
namespace App\Controller;

use App\Service\ValidationService;
use App\Exception\InvalidDataException;
use App\Entity\Client;
use App\ResponseHandler\ResponseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register")
     */
    public function register(Request $request, ValidationService $validation, 
        ResponseHandler $rh
    ): JsonResponse {
        try {
            if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
                throw new BadRequestHttpException('Invalid request format!');
            }

            $data = json_decode($request->getContent(), false);

            $validation->validateRequestData($data, 'registration');

            $client = $this->getDoctrine()
                ->getRepository(Client::class)
                ->findClient($data->register->username); 

            if ($client) {
                throw new InvalidDataException('Username already exists!');
            }
            if (strpos(strtolower($data->register->password), strtolower($data->register->firstname)) !== false
                || strpos(strtolower($data->register->password), strtolower($data->register->lastname)) !== false
            ) {
                throw new InvalidDataException('Password cannot contain firstname or lastname!');
            }
            if (!preg_match('/^(?=.*\d)[A-Za-z\d]{6,50}$/', $data->register->password)) {
                throw new InvalidDataException(
                    'Invalid password format! Must contain 1 letter and 1 number, 6-50 chars');
            }

            $salt = base_convert(sha1(mt_rand()), 16, 36);
            $entityManager = $this->getDoctrine()->getManager();

            $client = new Client();
            $client->setFirstname($data->register->firstname);
            $client->setLastname($data->register->lastname);
            $client->setUsername($data->register->username);
            $client->setPassword(sha1($salt.$data->register->password));
            $client->setSalt($salt);
            $client->setRole(Client::ROLE_USER);

            $entityManager->persist($client);
            $entityManager->flush();
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse();
    }
}

?>