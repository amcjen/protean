<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/
class PFImageFile {

	static private $instance;

	private function __construct() { }

	static public function getInstance() {

		if (self::$instance == NULL) {
			self::$instance = new PFImageFile();
		}

		return self::$instance;
	}

	static public function convertNonstandardImageToJpeg($filePath) {
		$type = self::getCommandLineImageMimetype($filePath);
		if (empty($type)) {
			throw new PFException('api', 'ATTEMPT_TO_CONVERT_UNSUPPORTED_IMAGE_TYPE_FAILED', E_USER_WARNING);
		}

		if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

			switch ($type) {
				case IMAGETYPE_JPEG:
				return basename(self::stripFileExtension($filePath) . '.jpg');
				break;
				case IMAGETYPE_GIF:
				return basename(self::stripFileExtension($filePath) . '.gif');
				break;
				case IMAGETYPE_PNG:
				return basename(self::stripFileExtension($filePath) . '.png');
				break;
			}
		}

		if ($type == IMAGETYPE_BMP || $type == IMAGETYPE_TIFF_II || $type == IMAGETYPE_TIFF_MM) {

			$baseName = self::stripFileExtension($filePath) . '.jpg';
			exec(PF_ANYTOPNM_EXECUTABLE_PATH . " $filePath | " . PF_PNMTOJPEG_EXECUTABLE_PATH . " > $baseName", $output, $returnValue);

			if ($returnValue == 0) {
				self::unlinkFile($filePath);
				return basename($baseName);
			} else {
				throw new PFException('api', 'ATTEMPT_TO_CONVERT_UNSUPPORTED_IMAGE_TYPE_FAILED', E_USER_WARNING);
			}
		}

		return false;
	}

	public static function createThumbnail($name, $filename, $newWidth, $newHeight) {
		$type = self::getCommandLineImageMimetype($name);
		switch ($type) {
			case IMAGETYPE_JPEG:
			$sourceImage = imagecreatefromjpeg($name);
			break;
			case IMAGETYPE_GIF:
			$sourceImage = imagecreatefromgif($name);
			break;
			case IMAGETYPE_PNG:
			$sourceImage = imagecreatefrompng($name);
			break;
			default:
			throw new PFException('api', 'IMAGE_TYPE_MUST_BE_JPG_GIF_PNG', E_USER_WARNING);
			break;
		}

		if (!isset($sourceImage)) {
			throw new PFException('api', 'IMAGE_MIME_TYPE_NOT_FOUND', E_USER_ERROR);
		}

		$oldX = imageSX($sourceImage);
		$oldY = imageSY($sourceImage);

		if ($oldX > $oldY) {
			$thumbWidth = $newWidth;
			$thumbHeight = $oldY * ($newHeight / $oldX);
		}

		if ($oldX < $oldY) {
			$thumbWidth = $oldX * ($newWidth / $oldY);
			$thumbHeight = $newHeight;
		}

		if ($oldX == $oldY) {
			$thumbWidth = $newWidth;
			$thumbHeight = $newHeight;
		}

		$destinationImage = imageCreateTrueColor($thumbWidth, $thumbHeight);
		imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $oldX, $oldY);

		switch ($type) {
			case IMAGETYPE_JPEG:
			imagejpeg($destinationImage, $filename, 95);
			break;
			case IMAGETYPE_GIF:
			imagegif($destinationImage, $filename);
			break;
			case IMAGETYPE_PNG:
			imagepng($destinationImage, $filename);
			break;
			default:
			throw new PFException('api', 'IMAGE_TYPE_MUST_BE_JPG_GIF_PNG', E_USER_WARNING);
			break;
		}

		imagedestroy($destinationImage);
		imagedestroy($sourceImage);
	}

	public static function isImageMimetypeValid($fileName, $mimeType) {
		if (empty($_FILES[$fileName]['mimetype'])) {
			return true;
		}

		if (in_array($mimeType, $this->attributes['mimetype'])) {
			return true;
		}

		list($major)	=	explode('/', $mimeType);

		foreach ($this->attributes['mimetype'] as $m) {
			$m =	explode('/', $m);
			if ($m[1] == '*' && $m[0] == $major) {
				return true;
			}
		}

		return false;
	}

	public static function getCommandLineImageMimetype($filePath) {
		exec(PF_FILE_EXECUTABLE_PATH . " -i $filePath", $output, $returnValue);
		$mime = explode(' ', $output[0]);

		if ($returnValue == 0) {

			switch ($mime[1]) {
				case 'image/jpeg;':
					return IMAGETYPE_JPEG;
				case 'image/gif;':
					return IMAGETYPE_GIF;
				case 'image/png;':
					return IMAGETYPE_PNG;
				case 'image/x-ms-bmp;':
				case 'image/bmp;':
					return IMAGETYPE_BMP;
				case 'image/tiff;':
					return IMAGETYPE_TIFF_II;
				default:
					return 0;
			}
		} else {
			throw new PFException('api', 'ATTEMPT_TO_CONVERT_UNSUPPORTED_IMAGE_TYPE_FAILED', E_USER_WARNING);
		}
	}
	/* Pass in an array of allowed types.  Types are as follows:
	IMAGETYPE_GIF
	IMAGETYPE_JPEG
	IMAGETYPE_PNG
	IMAGETYPE_SWF
	IMAGETYPE_PSD
	IMAGETYPE_BMP
	IMAGETYPE_TIFF_II (intel byte order)
	IMAGETYPE_TIFF_MM (motorola byte order)
	IMAGETYPE_JPC
	IMAGETYPE_JP2
	IMAGETYPE_JPX
	IMAGETYPE_JB2
	IMAGETYPE_SWC
	IMAGETYPE_IFF
	IMAGETYPE_WBMP
	IMAGETYPE_XBM

	PFImageFile::IsImageExifValid('/some/file.txt', array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG));
	*/
	static public function isImageExifValid($filePath, $allowedTypes) {
		foreach($allowedTypes as $type) {

			if (exif_imagetype($filePath) == $type) {

				return true;
			}
		}
		return false;
	}

	public static function unlinkFile($filename) {
		if (file_exists(TR_ITEM_IMAGE_BASE_DIRECTORY . '/fullsize/' . $filename)) {
			unlink(TR_ITEM_IMAGE_BASE_DIRECTORY . '/fullsize/' . $filename);
		}

		if (file_exists(TR_ITEM_IMAGE_BASE_DIRECTORY . '/thumbnail/' . $filename)) {
			unlink(TR_ITEM_IMAGE_BASE_DIRECTORY . '/thumbnail/' . $filename);
		}
	}

	public static function stripFileExtension($filename) {
		$ext = strrchr($filename, '.');

		if ($ext !== false) {
			$filename = substr($filename, 0, -strlen($ext));
		}
		return $filename;
	}

	public static function getRealValueOfMaxSize($maxFileSize) {
		if (substr($maxFileSize, -1, 1) == 'G') {
			$maxFileSize = substr($maxFileSize, 0, count($maxFileSize)) . '000000000';
		} elseif (substr($maxFileSize, -1, 1) == 'M') {
			$maxFileSize = substr($maxFileSize, 0, count($maxFileSize)) . '000000';
		} elseif (substr($maxFileSize, -1, 1) == 'K') {
			$maxFileSize = substr($maxFileSize, 0, count($maxFileSize)) . '000';
		}
		return $maxFileSize;
	}
}
?>
