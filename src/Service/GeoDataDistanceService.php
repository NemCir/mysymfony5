<?php
namespace App\Service;

class GeoDataDistanceService {

    /**
     * Calculating distance based on longitudes and latitudes
     *
     * @param float       $lat1 Starting point latitude 
     * @param float       $lon1 Starting point longitude
     * @param float       $lat2 Ending point latitude
     * @param float       $lon2 Ending point longitude
     * @param string|null $unit desired distance unit
     * @return float      distance in desired unit
     */
    public function distanceLength($lat1, $lon1, $lat2, $lon2, $unit = null) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist  = acos($dist);
            $dist  = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit  = strtolower($unit);

            if ($unit == "km") {
                return ($miles * 1.609344);
            } else {
                return $miles;
            }
        }
    }
}

?>