# Imageprocessor
This is a standalone on the fly PHP image processing webservice who's only requirement is having GD enabled.

It optimizes and caches images so the next time the request is made to the same url, it simply loads it from cache which makes it extreamly fast.

It is a standalone webservice and accepts 3 different params:
 - url - Required: the url to the image / also works with external image urls
 - size - Optional: 1 of 3 values 'thumb', 'medium', 'full' which correspond to a 25%, 50% and 100% of the original size
 - folder - Optional: The name of the cache folder to place the cache in, this is relative to the root cache folder which is './cache/'
 
 # Usage
 ```
 /index.php?url=[URL_TO_IMAGE]&size=[thumb|medium|full]&folder=[FOLDER_NAME]
 ```

