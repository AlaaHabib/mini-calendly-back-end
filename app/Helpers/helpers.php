<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;

if (!function_exists('timeSteps')) {
    function timeSteps($start_date, $end_date, $start, $end, $step, $reserved = []){
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $result=[];

        foreach ($dates as $date) {
            $date = $date->format('Y-m-d');
            $startTime = Carbon::createFromFormat('H:i:s', $start);
            $endTime = Carbon::createFromFormat('H:i:s', $end);

            while ($startTime->lt($endTime)) {

                $item = [];
                array_push($item, $startTime->format('H:i:s'));
                $startTime->addMinutes($step);
                array_push($item, $startTime->format('H:i:s'));
                $result[$date][] = $item;
            }
        }
        if(count($reserved) > 0){
            foreach ($reserved as $item) {
                $time[] = $item['start_time'];
                $time[] = $item['end_time'];
                $date = $item['date'];

                $index = array_search($time, $result[$date]);
                // dump($time, $result[$date], $item['date']);
                unset($result[$date][$index]);
                // dump($time, $result[$date], $item['date']);
                
                // $result[$date] = array_diff($result[$date],$time);
                $time = [];
            }
        }
        return $result;
    }
}
?>
