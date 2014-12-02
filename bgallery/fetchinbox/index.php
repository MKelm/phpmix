<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
header('Content-Type: text/plain; charset=utf-8');

define("BG_INDEX", 1);

include_once(__DIR__."/../config.php");
include_once(__DIR__."/helper.php");
include_once(__DIR__."/../classes/database.php");

include_once(__DIR__."/../classes/database.php");
$galleryDB = new \BGallery\Database($config["database"]);

echo "-> Update galleries by email inbox\n";

$mbox = imap_open(
  $config["inbox"]["mailbox"]."INBOX",
  $config["inbox"]["username"],
  $config["inbox"]["password"]
);

$maxMessages = $config["inbox"]["fetchmsgs"];
$numMessages = imap_num_msg($mbox);
if ($numMessages < $maxMessages)
  $maxMessages = $numMessages;


$attachmentsCount = 0;
$count = 0;
for ($m = $numMessages; $m > ($numMessages - $maxMessages); $m--) {
  $header = imap_header($mbox, $m);

  $fromEmail = $header->from[0]->mailbox."@".$header->from[0]->host;

  $subjectParts = getSubjectParts($fromEmail, $header->subject);

  $user = $galleryDB->getUserByEmail($subjectParts["email"]);
  if (empty($user)) {
    echo "-> Skip email $count, unknown user\n";
    continue;
  } else {
    echo "-> Valid user, email $count\n";
    $userId = $user["id"];
    unset($user);
  }

  $gallery = $galleryDB->getGalleryByName($userId, $subjectParts["gallery"]);
  if (empty($gallery)) {
    echo "-> Add new gallery for user, email $count\n";
    $galleryId = $galleryDB->insertGallery($userId, $subjectParts["gallery"]);
    unset($gallery);
  } else {
    echo "-> Valid gallery, email $count\n";
    $galleryId = $gallery["id"];
    unset($gallery);
  }

  $mailStruct = imap_fetchstructure($mbox, $m);
  $imageAttachments = getImageAttachments($mbox, $m, $mailStruct);
  if (count($imageAttachments) > 0) {
    $savedAttachmentsCount = saveImageAttachments(
      $imageAttachments, $galleryDB, $galleryId, $config["images"]
    );
    echo "-> Saved ".$savedAttachmentsCount." image attachments, email $count\n";
    $attachmentsCount += $savedAttachmentsCount;
  } else {
    echo "-> No image attachments, email $count\n";
  }

  if ($mailStruct->type == 1) {
    // multipart
    $body = imap_fetchbody($mbox, $m, "1");
  } else {
    $body = imap_body($mbox, $m);
  }
  if (!empty($body)) {
    $bodyTags = getGalleryTags($body);
  }
  if (!empty($body) && $bodyTags !== null) {
    echo "-> Save tags from email $count\n";
    //sqliteSetTags($subjectParts["folder"], $subjectParts["gallery"], $bodyTags);
  }

  $count++;
  if ($config["inbox"]["deletemsgs"] == true) {
    imap_delete($mbox, $m);
  }
}

if ($config["inbox"]["deletemsgs"] == true)
  imap_expunge($mbox);

echo "-> Fetched $count messages \n";
if ($count > 0)
  echo "--> with $attachmentsCount attachments \n";
if ($count > 0) {
  if ($config["inbox"]["deletemsgs"] == true)
    echo "-> Deleted $count messages\n";
  else
    echo "-> Deleted no messages \n";
}

imap_close($mbox);
