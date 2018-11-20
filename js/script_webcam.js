(function() {

  var streaming = false,
      video = document.querySelector('#video'),
      cover = document.querySelector('#cover'),
      canvas = document.querySelector('#canvas'),
      photo = document.querySelector('#photo'),
      startbutton  = document.querySelector('#snap'),
   // filter  = document.querySelector('input[name=type_filter]:checked').value;


      width = 320,
      height = 0;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);


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

  function takepicture() {
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      var data = canvas.toDataURL('image/png');

      // var form = document.getElementById("myAwesomeForm");

      var ImageURL = data;
      // Split the base64 string in data and contentType
      var block = ImageURL.split(";");
      // Get the content type
      var contentType = block[0].split(":")[1];// In this case "image/gif"
      // get the real base64 content of the file
      var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...."

      // document.write(realData);
      // document.write(contentType);
      var blob = b64toBlob(realData, contentType);
      var fd = new FormData();
      // fd.append("image", blob);
      fd.append("myfile", blob);
      // fd.append("myfile", 'blob');

      var xhr = new XMLHttpRequest();
       xhr.onreadystatechange = function(event) {
           if (this.readyState === XMLHttpRequest.DONE) {
             if (this.status === 200)
             {
                console.log(this.responseText);
               // obj = JSON.parse(this.responseText);
               // console.log(obj.path_file);
              // document.location.reload(true);
             }
             else
             {
               console.log("XHR Error : %d (%s)", this.status, this.statusText);
             }
             // html_parent.removeChild(html_progress);
           }
         };
      xhr.open('POST', '/fusion.php', true);
      // xhr.open('POST', '/membre.php?path=' + (obj), true);
      // document.location.reload(true);
      xhr.send(fd);
      // document.location.reload(true);
      // photo.setAttribute('src', data);

      }



    startbutton.addEventListener('click', function(ev){
      // console.log("CLICK");
        takepicture();
      ev.preventDefault();
    }, false);


})();
