# Imageprocessor
This is a PHP image processor webservice who's only requirement is having GD enabled.

It is a standalone webservice and accepts 3 different params:
 - url - Required: the url to the image / also works with external image urls
 - size - Optional: 1 of 3 values 'thumb', 'medium', 'full'
 - folder - Optional: The name of the folder to place the cache in
 
 # Usage
 `html
 /index.php?url=[URL_TO_IMAGE]&size=[thumb|medium|full]&folder=[FOLDER_NAME]
 `

