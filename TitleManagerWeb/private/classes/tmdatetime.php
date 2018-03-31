<?php

class TMDateTime extends DateTime {

  protected $datetime_string;

  function __construct(String $datetime) {
    // get datetime string in the format Y-m-d H:i:s.mmmmmmm
    try {
      TMDateTime::verify_format($datetime);
    }
    catch (WrongFormatException $e) {
      global $logger;
      $logger->log_error($e);
    }
    $this->datetime_string = $datetime;
    parent::__construct($this->get_datetime_short());
  }

  public function date_diff(TMDateTime $datetime) {
    // returns the number of days difference between the two dates in the two TMDateTimes
    // so 180327 23:59:00 and 180328 00:01:00 would be a difference of 1 day, even if they are just a couple of minutes of actual time apart
    return (new DateTime($this->get_date()))->diff(new DateTime($datetime->get_date()));
  }

  public function time_diff(TMDateTime $datetime) {
    // returns the difference between the two TMDateTimes
    // same as diff on DateTime objects, but for TMDateTime objects
    return (new DateTime($this->get_datetime_short()))->diff(new DateTime($datetime->get_datetime_short()));
  }


  public function get_datetime_long() {
    return $this->datetime_string;
  }

  public function get_datetime_short() {
    return substr($this->get_datetime_long(),0,19);
  }

  public function get_date() {
    return substr($this->get_datetime_long(),0,10);
  }

  public function get_time_long() {
    return substr($this->get_datetime_long(),11,8);
  }

  public function get_time_short() {
    return substr($this->get_datetime_long(),11,5);
  }

  public function get_millisecs() {
    return substr($this->get_datetime_long(),20,7);
  }

  public function get_year() {
    return substr($this->get_datetime_long(),0,4);
  }

  public function get_presentable_datetime() {
    return date_format($this, 'jS \o\f F Y \a\t H:i');
  }

  public function get_dayofweek() {
    return date_format($this, "l");
  }

  public static function verify_format($datetime) {
    if (empty($datetime)) {
      return FALSE;
    }
    elseif (!is_string($datetime)) {
      return FALSE;
    }
    elseif (!(strlen($datetime) == strlen(generate_datetime_for_sql()))) {
      return FALSE;
    }
    elseif (! ( is_numeric(substr($datetime,0,4)) && is_numeric(substr($datetime,5,2)) && is_numeric(substr($datetime,8,2)) ) ) {
      // not correct format for the date
      throw new WrongFormatException("Incorrect format for the date in " . __CLASS__ . ".");
      return FALSE;
    }
    elseif (! ( is_numeric(substr($datetime,11,2)) && is_numeric(substr($datetime,14,2)) && is_numeric(substr($datetime,17,2)) && is_numeric(substr($datetime,20,7)) ) ) {
      // not correct format for the time
      throw new WrongFormatException("Incorrect format for the time in " . __CLASS__ . ".");
      return FALSE;
    }
    else {
      return TRUE;
    }
  }


  // public static function validateDateTime($date_string, $format = "Y-m-d H:i:s") {
  //   if (!(strlen($date_string) == strlen("0000-00-00 00:00:00.0000000"))) {
  //     return FALSE;
  //   }
  //   $date_part = substr($date_string,0,19);
  //   $millisecond_part = substr($date_string,20,7);
  //   $date = DateTime::createFromFormat($format, $date_part);
  //   return $date && ($date->format($format) === $date_part) && is_numeric($millisecond_part);
  // }





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
