<?php 

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Entity\Client;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthService {

    /**
     * Authenticating request headers and client data
     *
     * @param object  $request  request object
     * @param object  $doctrine doctrine object
     * @param boolean $admin    validate if admin or user
     * @return object           validated request with added client
     */
    public function authenticateRequest($request, $doctrine, $admin = false) {
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            throw new BadRequestHttpException('Invalid request format!');
        }

        $data = json_decode($request->getContent(), false);

        $client = $doctrine->getRepository(Client::class)
            ->findOneBy(['username' => $data->auth->username]);

        if (!$client || sha1($client->getSalt().$data->auth->password) !== $client->getPassword()) {
            throw new AccessDeniedHttpException('Invalid authentication!');
        }
        if ($admin && $client->getRole() != Client::ROLE_ADMIN) {
            throw new AccessDeniedHttpException('You cannot access this page!');
        }

        $data->client = $client;

        return $data;
    }

}

?>