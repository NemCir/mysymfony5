<?php
namespace App\Controller;

use App\Service\AuthService;
use App\Entity\Airport;
use App\Entity\Route as FlightRoute;
use App\Entity\City;
use App\Entity\Client;
use App\ResponseHandler\ResponseHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImportDataController extends AbstractController {
    /**
     * @Route("/import/airports")
     */
    public function airports(Request $request, AuthService $authService, 
        ResponseHandler $rh
    ): JsonResponse {
        try {
            $authService->authenticateRequest($request, $this->getDoctrine(), true);

            $file_handle = fopen('../db_imports/airports.txt', "r");

            $airportIds = $this->getDoctrine()
                ->getRepository(Airport::class)
                ->getAllAirportIds();
            $cityUniqueData = $this->getDoctrine()
                ->getRepository(City::class)
                ->getCityUniqueData();
            $entityManager = $this->getDoctrine()->getManager();
            $count = 0;
            $batchSize = 700;

            while (($row = fgetcsv($file_handle, 1024, ",")) !== false) {
                if (in_array('\\N', [$row[0], $row[6], $row[7]])) continue;
                if (!in_array($row[2].$row[3], $cityUniqueData)) continue;
                if (in_array($row[0], $airportIds)) continue;
                $airport = new Airport();
                $airport->setAirportId($row[0]);
                $airport->setName($row[1]);
                $airport->setCity($row[2]);
                $airport->setCountry($row[3]);
                $airport->setIata($row[4]);
                $airport->setIcao($row[5]);
                $airport->setLatitude($row[6]);
                $airport->setLongitude($row[7]);
                $airport->setAltitude(intval($row[8]));
                $airport->setTimezone(intval($row[9]));
                $airport->setDst($row[10]);
                $airport->setTz($row[11]);
                $airport->setType($row[12]);
                $airport->setSource($row[13]);
                $entityManager->persist($airport);
                $count++;
                if (($count % $batchSize) === 0) {
                    $entityManager->flush();
                }
            }
            $entityManager->flush();
            $entityManager->clear();

            $dataObject = new \StdClass();
            $dataObject->message = 'Successfully added '.$count.' airports!'; 
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
            
        } 
        return $rh->successResponse($dataObject);
    }

    /**
     * @Route("/import/routes")
     */
    public function routes(Request $request, AuthService $authService, 
        ResponseHandler $rh
    ): JsonResponse {
        try {
            $authService->authenticateRequest($request, $this->getDoctrine(), true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

            $airportIds = $this->getDoctrine()
                ->getRepository(Airport::class)
                ->getAllAirportIds();
            $routesCodes = $this->getDoctrine()
            ->getRepository(FlightRoute::class)
            ->getAllRoutesCodes();

            $file_handle = fopen('../db_imports/routes.txt', "r");
            $count = 0;
            $batchSize = 700;

            //Only routes with both airports city defined, will be imported
            while (($row = fgetcsv($file_handle, 128, ",")) !== false) {
                if (!in_array($row[3], $airportIds)
                    || !in_array($row[5], $airportIds)
                    || in_array($row[0].$row[3].$row[5], $routesCodes)
                ) {
                    continue;
                }
                if ($row[1] == '\\N') {
                    $row[1] = 0;
                }
                $route = new FlightRoute();
                $route->setAirline($row[0]);
                $route->setAirlineId($row[1]);
                $route->setSourceAirport($row[2]);
                $route->setSourceAirportId(intval($row[3]));
                $route->setDestinationAirport($row[4]);
                $route->setDestinationAirportId(intval($row[5]));
                $route->setCodeshare($row[6]);
                $route->setStops($row[7]);
                $route->setEquipment($row[8]);
                $route->setPrice($row[9]);
                $route->setRoutecode($row[0].intval($row[3]).intval($row[5]));
                $entityManager->persist($route);
                $count++;
                if (($count % $batchSize) === 0) {
                    $entityManager->flush();
                    $entityManager->clear();
                }
            }
            $entityManager->flush();
            $entityManager->clear();

            $dataObject = new \StdClass();
            $dataObject->message = 'Successfully added '.$count.' routes!'; 
        } catch (\Exception $ex) {
            return $rh->errorResponse($ex);
            
        } 
        return $rh->successResponse($dataObject);
    }
}

?>