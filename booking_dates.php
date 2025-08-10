<?php
  // Booking start and end dates, yyyy-mm-dd
  // Determine current date
  $today = new DateTime();

  // Current date
  $dateString = $today->format('Y-m-d'); // e.g. "2025-08-09"

  // Determine academic year start and end
  $year = (int)$today->format("Y");
  $month = (int)$today->format("m");

  // The booking period is always from Sept 1 to May 31
  // You can book for the following academic year starting from June 1
  // i.e. after the previous booking period ends
  if ($month >= 6) {
    $BOOKING_START_DATE = "$year-09-01";
    $BOOKING_END_DATE = ($year + 1) . "-05-31";
  } else {
    $BOOKING_START_DATE = ($year - 1) . "-09-01";
    $BOOKING_END_DATE = "$year-05-31";
  }
?>