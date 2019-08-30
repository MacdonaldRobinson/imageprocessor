<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	class CustomImageProcessor {

		public $allowedTypes = array(
			"thumb" => "25",
			"medium" => "50",
			"full" => "100"
		);

		function get_server_cache_path($cache_folder_name, $size, $filename)
		{
			$cache_dir =__DIR__."/cache/";
			$cache_path =  $cache_dir."$cache_folder_name/$size/$filename";

			return $cache_path;
		}

		function output_file($path)
		{
			header("Content-Type: image/jpg");
			echo file_get_contents($path);
			exit;
		}

		function process_image_url($url, $size, $cache_folder_name="")
		{
			$path_info = pathinfo($url);
			$cache_path = $this->get_server_cache_path($cache_folder_name, $size, $path_info["basename"]);
			$cache_pathinfo = pathinfo($cache_path);

			if(file_exists($cache_path))
			{
				$this->output_file($cache_path);
			}

			if(!isset($this->allowedTypes[$size]))
			{
				echo "size $size not allowed";
				return;
			}

			$sizeVal = $this->allowedTypes[$size];

			$image = imagecreatefromjpeg($url);
	
			if (!$image)
			{
				echo "error loading url: $url <br>";
				return;
			}

			$percent = floatval($sizeVal) / 100;

			// Get new dimensions
			list($width, $height) = getimagesize($url);
			$new_width = $width * $percent;
			$new_height = $height * $percent;

			// Resample
			$image_p = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			// Create Cache Dir
			@mkdir($cache_pathinfo["dirname"], 0777, true);

			// Output
			imagejpeg($image_p, $cache_path);
			
			$this->output_file($cache_path);
		}
	}

	$url = isset($_GET["url"])? $_GET["url"] : '';
	$size = isset($_GET["size"])? $_GET["size"] : 'full';
	$folder = isset($_GET["folder"])? $_GET["folder"] : '';

	$image_processor = new CustomImageProcessor();
	$image_processor->process_image_url($url, $size, $folder);

?>