<?php


// analyses statistics
$analysesAssignPending = [];
$analysesResultPending = [];
$analysesToVerify = [];
$analysesVerified = [];

$analysesData = $client->get('analysis')->getBody()->getContents();
$analysesData = json_decode($analysesData);
$analysesItemCount = $analysesData->count;

foreach ($analysesData->items as $item) {
  if ($item->review_state === 'sample_received') {
    $analysesAssignPending[] = $item;
    $analysesResultPending[] = $item;
  }
  if ($item->review_state === 'to_be_verified') {
    $analysesToVerify[] = $item;
  }
  if ($item->review_state === 'verified') {
    $analysesVerified[] = $item;
  }
}

// analysis requests data
$arReceptionPending = [];
$arResultsPending = [];
$arToVerify = [];
$arVerified = [];
$arPublished = [];

$arData = $client->get('analysisrequest')->getBody()->getContents();
$arData = json_decode($arData);
$arItemCount = $arData->count;

foreach ($arData->items as $item) {
  if ($item->review_state === 'sample_due') {
    $arReceptionPending[] = $item;
  }
  if ($item->review_state === 'sample_received') {
    $arResultsPending[] = $item;
  }
  if ($item->review_state === 'to_be_verified') {
    $arToVerify[] = $item;
  }
  if ($item->review_state === 'verified') {
    $arVerified[] = $item;
  }
  if ($item->review_state === 'published') {
    $arPublished[] = $item;
  }
}

// worksheet statistics 

$worksheetResultPending = [];
$worksheetToVerify = [];
$worksheetVerified = [];

$worksheetData = $client->get('worksheet')->getBody()->getContents();
$worksheetData = json_decode($worksheetData);
$worksheetItemCount = $worksheetData->count;

foreach ($worksheetData->items as $item) {

  if ($item->review_state === 'open') {
    $worksheetResultPending[] = $item;
  }
  if ($item->review_state === 'to_be_verified') {
    $worksheetToVerify[] = $item;
  }
  if ($item->review_state === 'verified') {
    $worksheetVerified[] = $item;
  }
}

// sample statistics

$sampleReceptionPending =[];
$sampleReceived = [];
$sampleRejected = [];



require_once('./templates/pages/index.php');

