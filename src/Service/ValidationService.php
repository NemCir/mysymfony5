<?php 

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidationService {
    /**
     * Authentication structure array
     *
     * @var array
     */
    private $authStructure = ['username' => 'mandatory', 'password' => 'mandatory'];
    
    /**
     * Requests structure array
     *
     * @var array
     */
    private $validationStructure = []; 

    /**
     * Class constructor
     */
    public function __construct() {
        $this->validationStructure = [
            'registration' => [
                'register' => [
                    'firstname' => 'mandatory',
                    'lastname'  => 'mandatory',
                    'username'  => 'mandatory',
                    'password'  => 'mandatory',
                ]
            ],
            'addCities' => [
                'auth'   => $this->authStructure,
                'cities' => [
                    'name'         => 'mandatory',
                    'country'      => 'mandatory',
                    'description'  => 'mandatory',
                ]
            ],
            'getAllCities' => [
                'auth'        => $this->authStructure,
                'commentsNum' => 'optional'
            ],
            'searchCitiesByName' => [
                'auth'           => $this->authStructure,
                'search'         => [
                    'city'        => 'mandatory',
                    'commentsNum' => 'optional'
                ]
            ],
            'addCityComment' => [
                'auth'    => $this->authStructure,
                'comment' => [
                    'cityId' => 'mandatory',
                    'text'   => 'mandatory'
                ]
            ],
            'updateCityComment' => [
                'auth'    => $this->authStructure,
                'comment' => [
                    'id'   => 'mandatory',
                    'text' => 'mandatory'
                ]
            ],
            'deleteCityComment' => [
                'auth'      => $this->authStructure,
                'comment' => [
                    'id'   => 'mandatory',
                ]
            ],
            'findCheapestFlight' => [
                'auth'   => $this->authStructure,
                'flight' => [
                    'sourceCity'         => 'mandatory',
                    'sourceCountry'      => 'mandatory',
                    'destinationCity'    => 'mandatory',
                    'destinationCountry' => 'mandatory',
                    'unit'               => 'optional'
                ]
            ],
        ];
    }

    /**
     * Validating request
     *
     * @param object $data
     * @param string $requestKey
     * @return void
     */
    public function validateRequestData($data, $requestKey) {
        $data = json_decode(json_encode($data), true);
        $this->validateDataStructure($data, $this->validationStructure[$requestKey]);
    }

    /**
     * Validating data structure against defined structure
     *
     * @param object $data                request data object
     * @param array  $validationStructure validation structure array
     * @return void
     */
    private function validateDataStructure($data, $validationStructure) {
        foreach ($validationStructure as $key => $val) {
            if ($val == 'mandatory' && (!isset($data[$key]) || $data[$key] == '' || is_array($data[$key]))
                || $val == 'optional' && isset($data[$key]) && is_array($data[$key])
            ) {
                throw new BadRequestHttpException(
                    "Invalid request format, property '".$key."' is missing or empty!"
                );
            } else if ($key == 'cities') {
                foreach ($data[$key] as $cityStructure) {
                    self::validateDataStructure($cityStructure, $val);    
                }
            } else if (is_array($val) && is_array($data[$key])) {
                self::validateDataStructure($data[$key], $val);
            }
        }
    }

}

?>