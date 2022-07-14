<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Service\GeoDataDistanceService;
use App\Service\ValidationService;
use App\Service\CheapestFlightService;
use App\Entity\Client;
use App\Entity\City;
use App\Entity\Comment;
use App\Entity\Airport;
use App\Entity\Route as FlightRoute;
use App\Exception\NoDataFoundException;
use App\ResponseHandler\ResponseHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CityController extends AbstractController {
    
    /**
     * @Route("/city/addCities")
     */
    public function addCities(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine(), true);

            $validation->validateRequestData($data, 'addCities');

            if (!empty($data->cities)) {
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($data->cities as $newCity) {
                    $city = new City();
                    $city->setName($newCity->name);
                    $city->setCountry($newCity->country);
                    $city->setDescription($newCity->description);
                    $entityManager->persist($city);
                }
                $entityManager->flush();
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
            
        } 
        return $rh->successResponse();
    }

    /**
     * @Route("/city/getAllCities")
     */
    public function getAllCities(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'getAllCities');

            $dataObject = new \StdClass();

            $cities = $this->getDoctrine()
                ->getRepository(City::class)
                ->findAll();
            if (isset($data->commentsNum) && is_numeric($data->commentsNum) && $data->commentsNum >= 0) {
                $limitComments = $data->commentsNum;
            } else {
                $limitComments = null;
            }

            foreach ($cities as $city) {
                $cityObject              = new \StdClass();
                $cityObject->name        = $city->getName();
                $cityObject->country     = $city->getCountry();
                $cityObject->description = $city->getDescription();
                $cityObject->comments    = [];
                $comments = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findBy(['city' => $city->getId()], ['insertDate' => 'DESC', 'modifiedDate' => 'DESC'], $limitComments);
                
                foreach ($comments as $comment) {
                    $commentObject             = new \StdClass();
                    $commentObject->id         = $comment->getId();
                    $commentObject->text       = $comment->getText();
                    $commentObject->insertDate = $comment->getInsertDate()->format('Y-m-d H:i:s');
                    if ($comment->getModifiedDate()) {
                        $commentObject->modifyDate = $comment->getModifiedDate()->format('Y-m-d H:i:s');
                    }
                    $cityObject->comments[]    = $commentObject;
                }
                $dataObject->cities[] = $cityObject;
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse($dataObject);
    }

    /**
     * @Route("/city/searchCitiesByName")
     */
    public function searchCitiesByName(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'searchCitiesByName');

            $dataObject = new \StdClass();

            $cities = $this->getDoctrine()
                ->getRepository(City::class)
                ->findBy(['name' => $data->search->city]);
            if (isset($data->search->commentsNum) 
                && is_numeric($data->search->commentsNum) 
                && $data->search->commentsNum >= 0
            ) {
                $limitComments = $data->search->commentsNum;
            } else {
                $limitComments = null;
            }
            if (!$cities) {
                throw new NoDataFoundException('Cities do not exist!');
            }
            foreach ($cities as $city) {
                $cityObject              = new \StdClass();
                $cityObject->name        = $city->getName();
                $cityObject->country     = $city->getCountry();
                $cityObject->description = $city->getDescription();
                $cityObject->comments    = [];
                $comments = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->findBy(['city' => $city->getId()], ['insertDate' => 'DESC', 'modifiedDate' => 'DESC'], $limitComments);
                
                foreach ($comments as $comment) {
                    $commentObject             = new \StdClass();
                    $commentObject->id         = $comment->getId();
                    $commentObject->text       = $comment->getText();
                    $commentObject->insertDate = $comment->getInsertDate()->format('Y-m-d H:i:s');
                    if ($comment->getModifiedDate()) {
                        $commentObject->modifyDate = $comment->getModifiedDate()->format('Y-m-d H:i:s');
                    }
                    $cityObject->comments[]    = $commentObject;
                }
                $dataObject->cities[] = $cityObject;
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse($dataObject);
    }

    /**
     * @Route("/city/addCityComment")
     */
    public function addCityComment(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'addCityComment');

            if (isset($data->comment)) {
                $city = $this->getDoctrine()
                    ->getRepository(City::class)
                    ->find($data->comment->cityId);
                if ($city) {
                    $comment = new Comment(); 
                    $comment->setText($data->comment->text);
                    $comment->setCity($city);
                    $comment->setClientId($data->client);
                    $comment->setInsertDate(new \DateTime(date('d-m-Y H:i:s')));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($comment);
                    $entityManager->flush();
                } else {
                    throw new NoDataFoundException('City does not exist!');
                }
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse();
    }

    /**
     * @Route("/city/updateCityComment")
     */
    public function updateCityComment(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'updateCityComment');

            if (isset($data->comment)) {
                $comment = $this->getDoctrine()
                    ->getRepository(Comment::class)
                    ->findOneBy(['id' => $data->comment->id, 'clientId' => $data->client->getId()]);
                if ($comment) {
                    $comment->setText($data->comment->text);
                    $comment->setModifiedDate(new \DateTime(date('d-m-Y H:i:s')));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                } else {
                    throw new NoDataFoundException('Comment does not exist!');
                }
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse();
    }

    /**
     * @Route("/city/deleteCityComment")
     */
    public function deleteCityComment(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'deleteCityComment');

            if (isset($data->comment->id)) {
                $comment = $this->getDoctrine()
                    ->getRepository(Comment::class)
                    ->findOneBy(['id' => $data->comment->id]);
                if ($comment && $comment->getClientId()->getId() == $data->client->getId()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($comment);
                    $entityManager->flush();
                } else if (!$comment) {
                    throw new NoDataFoundException('Comment does not exist!');
                } else {
                    throw new NoDataFoundException('Comment does not belong to client!');
                }
            }
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse();
    }

    /**
     * @Route("/city/findCheapestFlight")
     */
    public function findCheapestFlight(Request $request, AuthService $authService, 
        ValidationService $validation, ResponseHandler $rh, CheapestFlightService $cfs, GeoDataDistanceService $gds
    ): JsonResponse {
        try {
            $data = $authService->authenticateRequest($request, $this->getDoctrine());

            $validation->validateRequestData($data, 'findCheapestFlight');

            $sourceAirportIds       = [];
            $destinationAirportIds  = [];
            $nextQPrepared          = [];
            $prices                 = [];

            $unit = 'mi';
            if (isset($data->flight->unit) && $data->flight->unit) {
                $unit = $data->flight->unit;
            }

            $routes = $this->getDoctrine()
                ->getRepository(FlightRoute::class)
                ->findAll();
            
            foreach ($routes as $route) {
                $prices[$route->getSourceAirportId()][$route->getDestinationAirportId()] = $route->getPrice();
                $nextQPrepared[$route->getDestinationAirportId()] = PHP_INT_MAX;
                
            }

            $airports = $this->getDoctrine()
                ->getRepository(Airport::class)
                ->findBy(['city' => [$data->flight->sourceCity, $data->flight->destinationCity],
                    'country' => [$data->flight->sourceCountry, $data->flight->destinationCountry]]);

            //Cities may have multiple airports, thus we check each combination for cheapest route
            
            foreach ($airports as $airport) {
                if ($airport->getCity() == $data->flight->sourceCity) {
                    $sourceAirportIds[] = $airport->getAirportId();
                } else {
                    $destinationAirportIds[] = $airport->getAirportId();
                }
            }

            $cheapestFlightData = $cfs->calculateCheapestFlight(
                $sourceAirportIds, $destinationAirportIds, $prices, $nextQPrepared
            );
            
            $flightPath = $cheapestFlightData['flightPath'];
            $minPrice   = $cheapestFlightData['minPrice'];

            if (empty($flightPath)) {
                throw new NoDataFoundException('Flight does not exist!');
            }

            $ap   = [];
            $fRoutes     = [];
            $totalLength = 0;

            $airportsRaw = $this->getDoctrine()
            ->getRepository(Airport::class)
            ->findBy(['airportId' => $flightPath]);
            
            foreach ($airportsRaw as $airport) {
                $ap[$airport->getAirportId()] = $airport;
            }
            
            for ($i = 1; $i < count($flightPath); $i++) {
                $routeObj = new \StdClass();
                $routeObj->from   = $ap[$flightPath[$i-1]]->getCity() . ' (' . $ap[$flightPath[$i-1]]->getName() . ')';
                $routeObj->to     = $ap[$flightPath[$i]]->getCity() . ' (' . $ap[$flightPath[$i]]->getName() . ')';
                $routeObj->price  = $prices[$flightPath[$i-1]][$flightPath[$i]];
                $routeObj->length = round(
                    $gds->distanceLength(
                        $ap[$flightPath[$i-1]]->getLatitude(), 
                        $ap[$flightPath[$i-1]]->getLongitude(),
                        $ap[$flightPath[$i]]->getLatitude(), 
                        $ap[$flightPath[$i]]->getLongitude(),
                        $unit),
                    3
                );
                $totalLength += $routeObj->length;
                $fRoutes[] = $routeObj;
            }

            $dataObj = new \StdClass();
            $dataObj->totalPrice   = round($minPrice, 2);
            $dataObj->totalLength  = $totalLength;
            $dataObj->flightRoutes = $fRoutes;
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
        } 
        return $rh->successResponse($dataObj);
    }
}