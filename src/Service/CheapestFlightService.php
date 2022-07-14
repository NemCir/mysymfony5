<?php
namespace App\Service;

class CheapestFlightService {

    /**
     * Calculating shortest path between two points in multi-dimensional array
     * checking multiple starting and ending points, coming from same source
     * and destination cities
     *
     * @param int   $sourceAirportIds      starting point
     * @param int   $destinationAirportIds destination point
     * @param array $prices                array of path costs
     * @param array $nextQPrepared         array of next point distances
     * @return array                       array of shortest path points and total cost 
     */
    public function calculateCheapestFlight($sourceAirportIds, $destinationAirportIds, $prices, $nextQPrepared) {
        $minPrice   = PHP_INT_MAX;
        $flightPath = [];

        foreach ($sourceAirportIds as $sourceAirportId) {
            foreach ($destinationAirportIds as $destinationAirportId) {
                $nextQ                   = $nextQPrepared;
                $previousQ               = [];
                $nextQ[$sourceAirportId] = 0;

                if (!array_key_exists($destinationAirportId, $nextQ)) {
                    continue;
                }

                while (!empty($nextQ)) {
                    $cheapestId = array_search(min($nextQ), $nextQ);

                    if (!isset($prices[$cheapestId])) {
                        unset($nextQ[$cheapestId]);
                        continue;
                    }

                    foreach($prices[$cheapestId] as $neighborId => $cost) {
                        if (isset($nextQ[$neighborId]) 
                            && $nextQ[$cheapestId] + $cost <= $nextQ[$neighborId]) {
                            $nextQ[$neighborId]     = $nextQ[$cheapestId] + $cost;
                            $previousQ[$neighborId] = [$cheapestId, $nextQ[$neighborId]];
                        }
                    }
                    if ($cheapestId == $destinationAirportId) break;
                    unset($nextQ[$cheapestId]);
                }
                
                if (isset($previousQ[$destinationAirportId][1]) 
                    && $minPrice <= $previousQ[$destinationAirportId][1]
                ) {
                    continue;
                } 
                
                $minPrice   = $previousQ[$destinationAirportId][1];
                $position   = $destinationAirportId;
                $flightPath = [];
                
                while ($position != $sourceAirportId) {
                    $flightPath[] = $position;
                    $position     = $previousQ[$position][0];
                }
                $flightPath[] = $sourceAirportId;
                $flightPath   = array_reverse($flightPath);
            }
        }
        return ['flightPath' => $flightPath, 'minPrice' => $minPrice];
    }
}

?>