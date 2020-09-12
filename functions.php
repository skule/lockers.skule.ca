<?php
function is_email_utoronto($email){
  $suffixes = array("@utoronto.ca", "@skule.ca", ".skule.ca", "@mail.utoronto.ca");

  foreach ($suffixes as $suffix) {
    $len = strlen($suffix);

    if(!$len)
      continue;

    if(substr($email, -$len) === $suffix)
      return true;
  }

  return false;
}
