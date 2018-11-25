(function() {

  var streaming = false,
    video = document.querySelector('#video'),
    cover = document.querySelector('#cover'),
    canvas = document.querySelector('#canvas'),
    photo = document.querySelector('#photo'),
    startbutton = document.querySelector('#snap'),
    // filter  = document.querySelector('input[name=type_filter]:checked').value;


    width = 320,
    height = 0;

  if (navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({
        video: true
      })
      .then(function(stream) {
        video.srcObject = stream;
      })
      .catch(function(err0r) {
        console.log("Something went wrong!");
      });
  }

  video.addEventListener('canplay', function(ev) {
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth / width);
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

    var blob = new Blob(byteArrays, {
      type: contentType
    });
    return blob;
  }

  function takepicture() {
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    var data = canvas.toDataURL('image/png');


    var ImageURL = data;

    var block = ImageURL.split(";");

    var contentType = block[0].split(":")[1];

    var realData = block[1].split(",")[1];


    var blob = b64toBlob(realData, contentType);
    var fd = new FormData();

    fd.append("myfile", blob);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(event) {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          console.log(this.responseText);
        } else {
          console.log("XHR Error : %d (%s)", this.status, this.statusText);
        }
        // html_parent.removeChild(html_progress);
      }
    };
    xhr.open('POST', '/fusion.php', true);
    xhr.send(fd);

  }

  startbutton.addEventListener('click', function(ev) {
    takepicture();
    ev.preventDefault();
  }, false);

})();
