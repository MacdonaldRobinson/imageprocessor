<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	class CustomImageProcessor {

		public $allowedTypes = array(
			"thumb" => "25",
			"medium" => "50",
			"full" => "100",
			"custom" => "custom",
		);

		function get_server_cache_path($cache_folder_name, $size, $filename, $custom_width, $custom_height)
		{
			$cache_dir =__DIR__."/cache/";

			if($size == "custom")
			{
				$size = $size."/".$custom_width."x".$custom_height;
			}

			$cache_path =  $cache_dir."$cache_folder_name/$size/$filename";

			return $cache_path;
		}

		function output_file($path)
		{
			header("Content-Type: image/gif");
			echo file_get_contents($path);
			exit;
		}

		function process_image_url($url, $size, $cache_folder_name="", $custom_width, $custom_height)
		{
			$path_info = pathinfo($url);

			$cache_path = $this->get_server_cache_path($cache_folder_name, $size, $path_info["basename"], $custom_width, $custom_height);
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
			$image = @imagecreatefromjpeg($url);

			if (!$image)
			{
				$default_image = '';

				$thumb_default = "http://" . $_SERVER['SERVER_NAME'] . "/wp-content/themes/rohitcommunities/images/qpm/qpm-fallback.gif";
				$large_default = "http://" . $_SERVER['SERVER_NAME'] . "/wp-content/themes/rohitcommunities/images/qpm/qpm-fallback-large.gif";

				if($size == 'thumb')
				{
					$default_image = $thumb_default;
				}
				else
				{
					$default_image = $large_default;
				}

				header("Content-Type: image/gif");
				echo file_get_contents($default_image);

				exit;
			}

			// Get new dimensions
			list($width, $height) = getimagesize($url);

			if($width < 1600 )
			{
				$sizeVal = $this->allowedTypes["full"];
			}

			if($sizeVal == "custom")
			{
				$actual_ratio = $width / $height;
				$custom_ratio = $custom_width / $custom_height;

				if($custom_width >= $custom_height)
				{
					$custom_width = $custom_height;
					$custom_height = $custom_width / $actual_ratio;
				}
				else if($custom_width < $custom_height)
				{
					$custom_height = $custom_width / $actual_ratio;
				}

				$new_width = $custom_width;
				$new_height = $custom_height;
			}
			else
			{
				$percent = floatval($sizeVal) / 100;
				$new_width = $width * $percent;
				$new_height = $height * $percent;
			}

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
	$width = isset($_GET["width"])? $_GET["width"] : '';
	$height = isset($_GET["height"])? $_GET["height"] : '';

	$image_processor = new CustomImageProcessor();
	$image_processor->process_image_url($url, $size, $folder, $width, $height);

?>