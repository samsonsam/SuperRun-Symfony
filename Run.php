<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 18.12.17
 * Time: 02:49
 */

namespace App\Entity;


use DateTime;
use UnexpectedValueException;

class Run
{
    public $Date;
    public $Distance;
    public $Time;

    function __construct($Date, $Distance, $Time)
    {
        $now = new DateTime();
        if (!isset($Date)) {
            echo '<script language="javascript">';
            echo 'alert("Überprüfen Sie ihr Datum!")';
            echo '</script>';
            throw new UnexpectedValueException('$Date was not set!');
        } elseif (strtotime($Date) == -1) {
            throw new UnexpectedValueException('$Date was formatted wrong!');
        } elseif (strtotime($Date) > $now->getTimestamp()) {
            echo '<script language="javascript">';
            echo 'alert("Datum kann nicht in der Zukunft sein!")';
            echo '</script>';
            throw new UnexpectedValueException('$Date was invalid!');
        } else {
            $this->Date = strtotime($Date);
        }


        if (!isset($Distance)) {
            echo '<script language="javascript">';
            echo 'alert("Überprüfen Sie ihre Distanz!")';
            echo '</script>';
            throw new UnexpectedValueException('$Distance was not set!');
        } elseif ($Distance <= 0) {
            echo '<script language="javascript">';
            echo 'alert("Überprüfen Sie ihre Distanz!")';
            echo '</script>';
            throw new UnexpectedValueException('$Distance can not be \'0\'!');
        } else {
            $this->Distance = $Distance;
        }

        if (!isset($Time)) {
            echo '<script language="javascript">';
            echo 'alert("Überprüfen Sie ihre Zeitangabe!")';
            echo '</script>';
            throw new UnexpectedValueException('$Time was not set!');
        } else {
            $temp = count(explode(':', $Time));
            if ($temp > 3 or $temp < 3) {
                echo '<script language="javascript">';
                echo 'alert("Zeit wurde nicht korrekt angegeben!")';
                echo '</script>';
                throw new UnexpectedValueException('$Time was formatted wrong!');
            } elseif ($temp == 3) {
                $temp = explode(':', $Time);
                if (is_numeric($temp[0]) and is_numeric($temp[1]) and is_numeric($temp[2]) and $temp[0] <= 99 and $temp[1] <= 59 and $temp[2] <= 59) {
                    $this->Time = $Time;
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Überprüfen Sie ihre Zeitangabe!")';
                    echo '</script>';
                    throw new UnexpectedValueException('$Time was not numeric!');
                }
            }
        }
    }

    function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    /**
     * @param \Run $data The data to be added to the json file specified above.
     */
    function addRunData(\Run $data)
    {
        global $JSON_uri;
        /**
         * @param string $sortON
         * <p>
         * This function sorts the json file after adding a new /Run object
         * </p>
         */
        $sortON = 'Date';

        $arr = readJSON($JSON_uri);
        if (count($arr) != 0) {
            foreach ($arr as $key => $element) {
                if ($element['Date'] == $data->Date) {
                    unset($arr[$key]);
                    print '<script>alert("Es bestand bereits ein Eintrag an diesem Tag. Dieser wurde mit dem soeben ersteltem Eintrag ersetzt.")</script>';
                }
            }
        }
        $arr[] = $data;
        $arr = array_sort($arr, $sortON, SORT_DESC);
        writeJSON($JSON_uri, $arr);
    }


    function deleteRunData(int $id)
    {
        global $JSON_uri;
        print '<script>alert("1")</script>';

        $arr = readJSON($JSON_uri);

        print '<script>alert("2")</script>';

        if (isset($arr[$id])) {
            print '<script>alert("3")</script>';
            unset($arr[$id]);
            print '<script>alert("4")</script>';
        }
        print '<script>alert("5")</script>';
        writeJSON($JSON_uri, $arr);
        print '<script>alert("6")</script>';
    }

    function getDetailData()
    {
        global $JSON_uri;

        $arr = readJSON($JSON_uri);
        $result = [];
        if ($arr) {
            foreach ($arr as $key => $elem) {
                $result [] = array(
                    'id' => $key,
                    'date' => date("d.m.Y", $elem["Date"]),
                    'distance' => $elem["Distance"],
                    'time' => $elem["Time"],
                    'average' => intval($elem["Distance"] / getHour($elem["Time"]))
                );
            }
            return $result;
        }
    }

    function getOverviewData()
    {
        global $JSON_uri;

        $data = readJSON($JSON_uri);
        if (count($data) != 0) {
            $total_activity = 0;
            $total_km = 0;
            $start_time = null;
            $end_time = null;
            //$i = 0;
            $hh = 0;
            $mm = 0;
            $ss = 0;
            $prev_run = null;

            foreach ($data as $elem) {
                global $total_activity, $activity_span, $total_km, $total_h, $start_time, $end_time, $i, $hh, $mm, $ss, $prev_run;

                if ($i == 0) {
                    $start_time = $elem["Date"];
                } elseif (count($data) == $i + 1) {
                    $end_time = $elem["Date"];
                }

                if ($prev_run and $prev_run != $elem["Date"]) {
                    $total_activity = $i + 1;
                } elseif (!$prev_run) {
                    $total_activity = $i + 1;
                }
                $total_km += $elem["Distance"];
                $time = explode(':', $elem["Time"]);
                $hh += $time[0];
                $mm += $time[1];
                $ss += $time[2];
                $i++;
                $prev_run = $elem["Date"];
            }

            $total_h = round($hh + $mm / 60 + $ss / 3600, 2, PHP_ROUND_HALF_UP);
            if (isset($end_time)) {
                $difference_in_seconds = $start_time - $end_time;
            } else {
                $difference_in_seconds = 86400;
            }

            $activity_span = $difference_in_seconds / 60 / 60 / 24;

            return array(
                'total_activity' => $total_activity,
                'activity_span' => $activity_span,
                'total_km' => $total_km,
                'total_h' => $total_h
            );
        } else {
            return array(
                'total_activity' => 0,
                'activity_span' => 0,
                'total_km' => 0,
                'total_h' => 0
            );
        }


    }

    function getHour($time)
    {
        $temp = explode(':', $time);
        return $temp[0] + $temp[1] / 60 + $temp[2] / 3600;
    }


    function readJSON(string $uri = __DIR__ . '/data.json')
    {
        return json_decode(file_get_contents($uri), TRUE);
    }

    /**
     * @param string $uri
     * @param $data
     * @return bool|int The function returns the number of bytes that were written to the file, or
     * false on failure.
     */
    function writeJSON(string $uri = __DIR__ . '/data.json', $data)
    {
        return file_put_contents($uri, json_encode($data));
    }

}