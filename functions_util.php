<?php

/* Dummy Hera Board Model */
function _util_getDummyBoardData() {
  return json_decode('{"name":"Hera","brand":{"placement":"top left"},"edge":{"profile":"small-round"},"endcaps":{"color":"stainless","type":"button","branding":"pineclifflogo"},"feet":{"type":"screw"},"handle":"none","groove":false,"length":"{calculated}","strips":[{"size":"xsmall","wood":"purpleheart","endGrain":false},{"size":"small","wood":"oak-red","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"medium","wood":"lyptus","endGrain":false},{"size":"small","wood":"oak-red","endGrain":false},{"size":"xsmall","wood":"purpleheart","endGrain":false}],"width":25}');
}

function GUID() {
  if (function_exists('com_create_guid') === true)
  {
      return strtolower(trim(com_create_guid(), '{}'));
  }

  return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
}
