<?php

class MyDateTime {

  private $datetime_string;

  function __construct($datetime="") {
    // get datetime string in the format Y-m-d H:i:s.mmmmmmm
    if (MyDateTime::test_datetime_format($datetime)) {
      // everything ok
    }
    else {
      $datetime = generate_datetime_for_sql();
    }
    $this->datetime_string = $datetime;
  }

  public function get_datetime_long() {
    return $this->datetime_string;
  }

  public function get_datetime_short() {
    return $this->get_date() . " " . $this->get_time();
  }

  public function get_date() {
    return substr($this->get_datetime_long(),0,10);
  }

  public function get_time() {
    return substr($this->get_datetime_long(),11,8);
  }

  public function get_millisecs() {
    return substr($this->get_datetime_long(),20,7);
  }

  public function get_year() {
    return substr($this->get_date(),0,4);
  }

  public function get_presentable_datetime() {
    return date_format(DateTime::createFromFormat("Y-m-d H:i:s",$this->get_datetime_short()), 'jS F Y \a\t G:i');
  }

  public function get_dayofweek() {
    return date_format(DateTime::createFromFormat("Y-m-d H:i:s",$this->get_datetime_short()), "l");
  }

  public static function test_datetime_format($datetime) {
    if (empty($datetime)) {
      return FALSE;
    }
    elseif (!is_string($datetime)) {
      return FALSE;
    }
    elseif (!MyDateTime::validateDateTime($datetime)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }


  public static function validateDateTime($date_string, $format = "Y-m-d H:i:s") {
    if (!(strlen($date_string) == strlen("0000-00-00 00:00:00.0000000"))) {
      return FALSE;
    }
    $date_part = substr($date_string,0,19);
    $millisecond_part = substr($date_string,20,7);
    $date = DateTime::createFromFormat($format, $date_part);
    return $date && ($date->format($format) === $date_part) && is_numeric($millisecond_part);
  }





  // public function create($in_datetime) {
  //
  //   $fractional_second_precision = SQL_DATETIME_FRAC_SEC_PRECISION;
  //   // $in_datetime = microtime(TRUE);
  //   $milli_secs =  $in_datetime - floor($in_datetime);
  //   $milli_secs =  substr($milli_secs, 1, $fractional_second_precision + 1);
  //   $datetime_format = "Y-m-d H:i:s";
  //
  //   $datetime =  date($datetime_format, $in_datetime) . $milli_secs;
  //   return $datetime;
  // }

  // public function convert_datetime2_to_datetimestamp($datetime2) {
  //   $datetimestamp = date_format($datetime2, 'Y-m-d, H:i:s');
  //   return $datetimestamp;
  // }
  //
  // public function convert_datetime2_to_precise_datetimestamp($datetime2) {
  //   $datetimestamp = date_format($datetime2, 'Y-m-d, H:i:s.u');
  //   return $datetimestamp;
  // }
  //
  // public function datetime_get_date_from_datetime2($datetime2) {
  //   $datetimestamp = date_format($datetime2, 'Y-m-d');
  //   return $datetimestamp;
  // }
  //
  // public function get_time_from_datetime2($datetime2) {
  //   $datetimestamp = date_format($datetime2, 'H:i:s');
  //   return $datetimestamp;
  // }
  //


}

?>
