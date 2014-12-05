<?php
/**
 * BGallery by IDX.codelab
 * Developed on CubieTruck
 *
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright Copyright (c) 2014 Martin Kelm
 */
namespace BGallery;

if (!defined("BG_INDEX"))
  die("Access denied");

include_once(__DIR__."/database/base.php");

class Database extends Database\Base {

  protected $_tableNameUsers = "bg_users";
  protected $_tableNameGalleries = "bg_galleries";
  protected $_tableNameImages = "bg_images";
  protected $_tableNameTags = "bg_tags";

  function __construct($config, $reset = false) {
    parent::__construct($config);
    $this->connect();
    if ($reset == true) {
      $this->truncate($this->_tableNameUsers);
      $this->truncate($this->_tableNameGalleries);
      $this->truncate($this->_tableNameImages);
      $this->truncate($this->_tableNameTags);
    }
  }

  // USER

  function insertUser($email, $name, $password) {
    try {
      $this->insert(
        $this->_tableNameUsers,
        array(
          "name" => $name, "email" => $email,
          "password" => hash('sha256', $password),
          "level" => 0
        )
      );
      return $this->_last_insert_id;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getUsers() {
    try {
      $results = $this->select(
        $this->_tableNameUsers, array("id", "name")
      );
      return $results;
    } catch (\Exception $e) {
      return array();
    }
  }

  function getUserByEmail($email) {
    try {
      $results = $this->select(
        $this->_tableNameUsers,
        array("id", "name", "email"),
        array(
          array("email", "=", $email)
        )
      );
      return current($results);
    } catch (\Exception $e) {
      return array();
    }
  }

  function getUserByLogin($name, $password) {
    try {
      $results = $this->select(
        $this->_tableNameUsers,
        array("id", "name", "email"),
        array(
          array("name", "=", $name),
          array("password", "=", $password)
        )
      );
      return $results;
    } catch (\Exception $e) {
      return array();
    }
  }

  // GALLERY

  function insertGallery($userId, $name) {
    try {
      $this->insert(
        $this->_tableNameGalleries,
        array(
          "user_id" => $userId, "name" => $name
        )
      );
      return $this->_last_insert_id;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getGalleryByName($userId, $name) {
    try {
      $results = $this->select(
        $this->_tableNameGalleries,
        array("id", "name"),
        array(
          array("user_id", "=", $userId),
          array("name", "=", $name)
        )
      );
      return current($results);
    } catch (\Exception $e) {
      return array();
    }
  }

  function countGalleries($userId) {
    try {
      $results = $this->count(
        $this->_tableNameGalleries, "id",
        array(array("user_id", "=", $userId))
      );
      return $results;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getGalleries($userId) {
    try {
      $results = $this->select(
        $this->_tableNameGalleries, array("id", "name"),
        array(array("user_id", "=", $userId))
      );
      return $results;
    } catch (\Exception $e) {
      return array();
    }
  }

  // IMAGE

  function insertImage($galleryId, $name, $ext) {
    try {
      $this->insert(
        $this->_tableNameImages,
        array(
          "gallery_id" => $galleryId, "name" => $name, "ext" => $ext
        )
      );
      return $this->_last_insert_id;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getImageByName($galleryId, $name, $ext) {
    try {
      $results = $this->select(
        $this->_tableNameImages,
        array("id", "name", "ext"),
        array(
          array("gallery_id", "=", $galleryId),
          array("name", "=", $name),
          array("ext", "=", $ext)
        )
      );
      return current($results);
    } catch (\Exception $e) {
      return array();
    }
  }

  function getImageById($imageId) {
    try {
      $results = $this->select(
        $this->_tableNameImages,
        array("id", "gallery_id", "name", "ext"),
        array(
          array("id", "=", $imageId)
        )
      );
      return current($results);
    } catch (\Exception $e) {
      return array();
    }
  }

  function countImages($galleryId) {
    try {
      $results = $this->count(
        $this->_tableNameImages, "id",
        array(array("gallery_id", "=", $galleryId))
      );
      return $results;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getImages($galleryId, $limit = null) {
    try {
      $results = $this->select(
        $this->_tableNameImages, array("id", "name", "ext"),
        array(array("gallery_id", "=", $galleryId)),
        null, null, $limit
      );
      return $results;
    } catch (\Exception $e) {
      return array();
    }
  }

  // TAGS

  function deleteGalleryTags($galleryId) {
    try {
      return $this->delete(
        $this->_tableNameTags,
        array(
          array("gallery_id", "=", $galleryId)
        )
      );
    } catch (\Exception $e) {
      return 0;
    }
  }

  function deleteImageTag($galleryId, $imageId, $tag) {
    try {
      return $this->delete(
        $this->_tableNameTags,
        array(
          array("gallery_id", "=", $galleryId),
          array("image_id", "=", $imageId),
          array("tag", "=", $tag)
        )
      );
    } catch (\Exception $e) {
      return 0;
    }
  }

  function imageTagExists($galleryId, $imageId, $tag) {
    try {
      return $this->count(
        $this->_tableNameTags,
        "tag",
        array(
          array("gallery_id", "=", $galleryId),
          array("image_id", "=", $imageId),
          array("tag", "=", $tag)
        )
      ) > 0;
    } catch (\Exception $e) {
      return false;
    }
  }

  function insertImageTag($galleryId, $imageId, $tag) {
    try {
      $this->insert(
        $this->_tableNameTags,
        array(
          "gallery_id" => $galleryId, "image_id" => $imageId, "tag" => $tag
        )
      );
      return $this->_last_insert_id;
    } catch (\Exception $e) {
      return 0;
    }
  }

  function getGalleryTags($galleryId) {
    try {
      // all images of one gallery have the same tags
      $firstImage = current($this->getImages($galleryId, 1));
      $results = $this->select(
        $this->_tableNameTags, array("tag"),
        array(
          array("gallery_id", "=", $galleryId),
          array("image_id", "=", $firstImage["id"])
        )
      );
      return $results;
    } catch (\Exception $e) {
      return array();
    }
  }

  function getImagesByTag($tag, $limit = 48) {
    try {
      $results = $this->select(
        $this->_tableNameTags, array("image_id"),
        array(
          array("tag", "=", $tag)
        ),
        null, null, $limit
      );
      $taggedImages = array();
      if (!empty($results)) {
        foreach ($results as $result) {
          $taggedImages[] = $this->getImageById($result["image_id"]);
        }
      }
      return $taggedImages;
    } catch (\Exception $e) {
      return array();
    }
  }

}
