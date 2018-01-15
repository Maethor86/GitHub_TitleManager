<?php

class MyDateTime {

  private $datetime_string;

  function __construct($datetime="") {
    // get datetime string in the format Y-m-d H:i:s.mmmmmmm
    if (test_datetime_format($datetime)) {
      // everything ok
    }
    else {
      $datetime = generate_datetime_for_sql();
    }
    $this->datetime_string = $datetime;
  }

  public function get_full_datetime() {
    return $this->datetime_string;
  }

  public function get_datetime() {
    return $this->get_date() . " " . $this->get_time();
  }

  public function get_date() {
    return substr($this->get_full_datetime(),0,10);
  }

  public function get_time() {
    return substr($this->get_full_datetime(),11,8);
  }

  public function get_millisecs() {
    return substr($this->get_full_datetime(),20,7);
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

  public function convert_datetime2_to_datetimestamp($datetime2) {
    $datetimestamp = date_format($datetime2, 'Y-m-d, H:i:s');
    return $datetimestamp;
  }

  public function convert_datetime2_to_precise_datetimestamp($datetime2) {
    $datetimestamp = date_format($datetime2, 'Y-m-d, H:i:s.u');
    return $datetimestamp;
  }

  public function datetime_get_date_from_datetime2($datetime2) {
    $datetimestamp = date_format($datetime2, 'Y-m-d');
    return $datetimestamp;
  }

  public function get_time_from_datetime2($datetime2) {
    $datetimestamp = date_format($datetime2, 'H:i:s');
    return $datetimestamp;
  }

}

?>
