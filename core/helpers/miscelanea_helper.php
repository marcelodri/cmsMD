<?php

// http://stackoverflow.com/a/25370978/6534992

if ( ! function_exists('file_upload_max_size')) {
  function file_upload_max_size() {
    static $max_size = -1;

    if ($max_size < 0) {
      // Start with post_max_size.
      $max_size = parse_size(ini_get('post_max_size'));

      // If upload_max_size is less, then reduce. Except if upload_max_size is
      // zero, which indicates no limit.
      $upload_max = parse_size(ini_get('upload_max_filesize'));
      if ($upload_max > 0 && $upload_max < $max_size) {
        $max_size = $upload_max;
      }
    }
    return $max_size;
  }
}

if ( ! function_exists('parse_size')) {
    function parse_size($size) {
      $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
      $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
      if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
      }
      else {
        return round($size);
      }
    }
}

if ( ! function_exists('date_range')) {
    function date_range( $first, $last, $step = '+1 day', $format = 'Y/m/d' ) {

      while((new DateTime($first))->getTimestamp() < (new DateTime($last))->getTimestamp()){
        $dates[] = date( $format, strtotime( $first ) );
        $current = strtotime( $step, strtotime( $first ) );
        $first = (new DateTime())->setTimestamp($current)->format('Y-m-d H:i:s');
      }

      return $dates;
    }
}