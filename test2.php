<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Base64 string to a file in form</title>
    </head>
    <body>
        <form id="myAwesomeForm" method="post" action="endpoint.php">
            <input type="text" id="filename" name="filename" /> <!-- Filename -->
            <input type="submit" id="submitButton" name="submitButton" /> <!-- Submit -->
        </form>
        <script>
            function b64toBlob(b64Data, contentType, sliceSize) {
                contentType = contentType || '';
                sliceSize = sliceSize || 512;

                var byteCharacters = atob(b64Data);
                var byteArrays = [];

                for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    var slice = byteCharacters.slice(offset, offset + sliceSize);

                    var byteNumbers = new Array(slice.length);
                    for (var i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }

                    var byteArray = new Uint8Array(byteNumbers);

                    byteArrays.push(byteArray);
                }

              var blob = new Blob(byteArrays, {type: contentType});
              return blob;
            }

            $("#myAwesomeForm").submit(function(e){
                e.preventDefault();
                appendFileAndSubmit();
            });

            function appendFileAndSubmit(){
                // Get the form
                var form = document.getElementById("myAwesomeForm");

                var ImageURL = "";
                // Split the base64 string in data and contentType
                var block = ImageURL.split(";");
                // Get the content type
                var contentType = block[0].split(":")[1];// In this case "image/gif"
                // get the real base64 content of the file
                var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...."

                // Convert to blob
                var blob = b64toBlob(realData, contentType);

                // Create a FormData and append the file
                var fd = new FormData(form);
                fd.append("image", blob);

                // Submit Form and upload file
                $.ajax({
                    url:"endpoint.php",
                    data: fd,// the formData function is available in almost all new browsers.
                    type:"POST",
                    contentType:false,
                    processData:false,
                    cache:false,
                    dataType:"json", // Change this according to your response from the server.
                    error:function(err){
                        console.error(err);
                    },
                    success:function(data){
                        console.log(data);
                    },
                    complete:function(){
                        console.log("Request finished.");
                    }
                });
            }
        </script>
    </body>
</html>
