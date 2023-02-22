<?php

namespace App;

/**
 * Date handling
 */
class Dates
{
    /**
     * Set selected dates
     * 
     * @param string Contains selected period
     * 
     * @return array Contains earlier date and later date of selected period
     */
    public static function finansesDates($period)
    {
        $dates = array(
            'earlierDate' => date("Y-m") . "-01",
            'laterDate' => date("Y-m") . "-31"
        );
        if ($period == "previousMonth") {
            if (date("m") == "01") {
                $month = "12";
                $year = date("Y") - 1;
            } else {
                $month = date("m") - 1;
                $year = date("Y");
            }
            $dates['earlierDate'] = $year . "-" . $month . "-01";
            $dates['laterDate'] = $year . "-" . $month . "-31";
        } else if ($period == "currentYear") {
            $dates['earlierDate'] = date("Y") . "-01-01";
            $dates['laterDate'] = date("Y") . "-12-31";
        } else if ($period == "other") {
            if (isset($_POST['beginingDate'])) {
                $dates['earlierDate'] = $_POST['beginingDate'];
                $dates['laterDate'] = $_POST['endingDate'];
                $_SESSION['earlierDate'] = $_POST['beginingDate'];
                $_SESSION['laterDate'] = $_POST['endingDate'];
            } else {
                $dates['earlierDate'] = $_SESSION['earlierDate'];
                $dates['laterDate'] = $_SESSION['laterDate'];
            }
        }
        return $dates;
    }

    /**
     * Set first and last day of a month from chosen date
     * 
     * @param string $date Chosen date
     * 
     * @return array Constains first and last dat of a month
     */
    public static function setLimitDates($date)
    {
        $earlierDate = substr_replace($date, '01', -2);
        $laterDate = substr_replace($date, '31', -2);
        $dates = array(
            'earlierDate' => $earlierDate,
            'laterDate' =>  $laterDate
        );
        return $dates;
    }
}
