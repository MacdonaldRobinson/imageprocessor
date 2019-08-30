# Imageprocessor
This is a standalone on the fly PHP image processing webservice who's only requirement is having GD enabled.

It is a standalone webservice and accepts 3 different params:
 - url - Required: the url to the image / also works with external image urls
 - size - Optional: 1 of 3 values 'thumb', 'medium', 'full'
 - folder - Optional: The name of the cache folder to place the cache in, this is relative to the root cache folder which is './cache/'
 
 # Usage
 ```url
 /index.php?url=[URL_TO_IMAGE]&size=[thumb|medium|full]&folder=[FOLDER_NAME]
 ```

