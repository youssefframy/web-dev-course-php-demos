# Checkpoint 04: File uploads

## Goal

Let visitors attach an image to the contact form, check that it really is an image, and save it under a name that cannot clash.

## Concepts

- A form can only send files when its `enctype` is `multipart/form-data`. With the default format, the browser sends just the file's name.
- `<input type="file" accept="image/png, image/jpeg, image/gif">` asks the browser to offer only those types in its file picker. It is a convenience, not a protection: anyone can still send any file.
- PHP saves each uploaded file in a temporary folder and describes it in `$_FILES`. `$_FILES['uploadedFile']['tmp_name']` is where it was saved, `['name']` is the name it had on the visitor's computer, `['size']` is its size in bytes, and `['error']` says whether the upload worked. PHP deletes the temporary file when the request ends, unless you move it.
- `is_uploaded_file()` confirms that a path really is a file PHP received in this request. `move_uploaded_file()` moves it to its permanent place.
- The visitor chooses the file's name and extension, so neither proves what it contains. `finfo_file()` reads the start of the file itself and reports its MIME type, such as `image/png`.
- Decide the saved file's extension from that MIME type, never from the visitor's name. The slides keep the visitor's extension. A file called `shell.php` that starts like a PNG would pass the type check, be saved as `.php` inside the website, and run as PHP when someone opened it.
- Two visitors can upload files with the same name. Adding a unique part, built from `time()`, `uniqid()` and `rand()`, means one never overwrites the other.
- `ini_get('upload_max_filesize')` reads PHP's setting for the largest upload. It is `2M` by default.

## Before you start

You need the contact form from checkpoint 03. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/03-multi-valued/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

The reference answers from here on leave out the `best_time[]` boxes from the last "your turn". Keep yours or remove them, as you like. Your `contact-form/` folder already has an `uploads/` folder for the saved files. Its `.gitignore` stops uploaded files from being committed. Start the server in `contact-form/`.

## Steps

1. Follow along: at the top of the PHP block, under `$comments = '';`, add:

   ```php
   $maxSize = ini_get('upload_max_filesize');
   ```

   Change the form's opening tag to:

   ```html
   <form enctype="multipart/form-data" name="contact_form" method="POST" action="index.php">
   ```

   Change the comments line to end with two `<br>`s, and add a file field under it:

   ```php
   <textarea id="comments" name="comments"><?= $comments ?></textarea><br><br>
   <label for="uploadedFile" style="display: inline-block; width: 100px;">Screenshot</label>
   <input type="file" id="uploadedFile" name="uploadedFile" accept="image/png, image/jpeg, image/gif"> (Max Size: <?= $maxSize ?>)
   <br><br>
   ```

   *Check: a file button, followed by `(Max Size: 2M)`.*

2. Follow along: in the PHP block, after the newsletter line and still inside the `if`, add:

   ```php
   if (isset($_FILES['uploadedFile']) && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) {
       $uploadedFile = $_FILES['uploadedFile']['tmp_name'];

       // Check what the file really is, and pick its extension from that
       $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
       $mimeType = finfo_file($fileInfo, $uploadedFile);
       $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];
       if (!array_key_exists($mimeType, $allowedTypes)) {
           die('Invalid file format!');
       }

       // Give the file a unique name, so uploads never overwrite each other
       $uploadFolder = 'uploads/';
       $uniqueId = time() . uniqid(rand());
       $origName = pathinfo($_FILES['uploadedFile']['name'], PATHINFO_FILENAME);
       $ext = $allowedTypes[$mimeType];
       $destFile = $uploadFolder . $origName . "_$uniqueId." . $ext;

       if (move_uploaded_file($uploadedFile, $destFile)) {
           echo '<p>File uploaded successfully.</p>';
       } else {
           echo '<p>There was an error uploading the file.</p>';
       }
   }
   ```

   `$allowedTypes` maps each accepted MIME type to the extension to save it with. `array_key_exists()` checks the type is one of its keys. `die()` stops the script there and prints its message. Checkpoint 05 replaces it with a proper error message.

3. Follow along: send the form with a PNG or JPG screenshot.

   *Check: `File uploaded successfully.`, and a new file in `uploads/`, for example `screenshot_17942400428091794256af1ee2ab8167.png`.* Upload the same file again. *Check: a second file, with a different number.*

4. Follow along: try to sneak a script through. Create a text file called `fake.png` that contains only `<?php echo 'hello';`, and upload it.

   *Check: `Invalid file format!`, and nothing new in `uploads/`.* The name says PNG, but `finfo_file()` reads the content and reports `text/x-php`.

5. Follow along: upload an image larger than 2MB. If you do not have one, any photo from a phone usually is.

   *Check: the page shows the other values, but no upload message and no new file.* PHP refused the file before your code ran, so `is_uploaded_file()` was false. Checkpoint 05 tells the visitor why.

6. Your turn: `print_r($_FILES)` at the top of the `if` and send the form once with a small image and once with the large one. Compare the `error` and `size` values. Look up `UPLOAD_ERR_INI_SIZE` on php.net, and find which `error` number it is. Remove the `print_r()`.

## Done when

- [ ] An image upload is saved in `uploads/` under a unique name.
- [ ] A non-image file is rejected, whatever its name.
- [ ] The saved file's extension comes from the MIME type.
- [ ] You can say what `error` value a too-large file gets, and why the code did not notice it.

## Previous / Next

Previous: [03 Multi-valued fields](../03-multi-valued/README.md). Next: [05 Server-side validation](../05-server-validation/README.md).
