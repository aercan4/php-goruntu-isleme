<!DOCTYPE html>
<html>
<head>
  <title>PHP Tabanlı Görüntü İşleme</title>
  <link rel="stylesheet" type="text/css" href="./dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="./dist/css/rangeslider.css">
  <link rel="stylesheet" type="text/css" href="./dist/css/style.css">
</head>
<body>

  <div class="container">
    <form action="islem.php" method="post" enctype="multipart/form-data" >
      <h1 class="main_title text-center">PHP Tabanlı Görüntü İşleme</h1>
<hr>
      <div class="bolum">
        <h3 class="title">1- RESİM YÜKLE</h3>
        <p class="aciklama">Yükleyeceğiniz resim sadece jpg ve png olabilir.(Max: 10mb) </p>
        <input type="file" name="dosya" />  

      </div>
      <div class="bolum">
        <h3 class="title">2- FİLTRE SEÇİMİ</h3>
        <p class="aciklama"> Aşağıdaki filter işlemlerinden birini seçebilirsiniz.</p>

        <div class="row">
          <div class="col-lg-2">
            <label class="img_radio">
             <input type="radio" name="filter" value="none" checked>
             <img src="./dist/img/main.jpg">
             <span>
              Filtre Yok
            </span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="desaturate" >
            <img src="./dist/img/main-desaturate.jpg">
            <span>Desaturate</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="emboss" >
            <img src="./dist/img/main-emboss.jpg">
            <span>Emboss</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="edgedetect" >
            <img src="./dist/img/main-edge-detect.jpg">
            <span>MainEdgeDetect</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="invert" >
            <img src="./dist/img/main-invert.jpg">
            <span>Invert</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="pixelate" >
            <img src="./dist/img/main-pixelate.jpg">
            <span>Pixelate</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="sepia" >
            <img src="./dist/img/main-sepia.jpg">
            <span>Sepia</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="canny" >
            <img src="./dist/img/main-canny.jpg">
            <span>Canny</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="filter" value="sharpen" >
            <img src="./dist/img/main-sharpen.jpg">
            <span>Sharpen</span>
          </label>
        </div>

      </div>


    </div>
    <div class="bolum">
      <h3 class="title">3- YANSIMA İŞLEMLERİ</h3>
      <p class="aciklama">Resmi yansıtmak isterseniz seçim yapabilirsiniz.</p>

      <div class="row">
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rebound" value="none" checked="" >
            <img src="./dist/img/main.jpg">
            <span>Yansıma yok</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rebound" value="rebound_x" >
            <img src="./dist/img/main-flip_x.jpg">
            <span>X' e göre yansıma</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rebound" value="rebound_y" >
            <img src="./dist/img/main-flip_y.jpg">
            <span>Y' e göre yansıma</span>
          </label>
        </div>
      </div>
    </div>
    <div class="bolum">
      <h3 class="title">4- DÖNDÜRME İŞLEMLERİ</h3>
      <p class="aciklama">Resmi döndürmek isterseniz seçim yapabilirsiniz.</p>

      <div class="row">
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rotate" value="none" checked="" >
            <img src="./dist/img/main.jpg">
            <span>Döndürme yok</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rotate" value="rotate90" >
            <img src="./dist/img/main-rotate-90.jpg">
            <span>Saat yönünde 90°</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rotate" value="rotate180" >
            <img src="./dist/img/main-rotate-180.jpg">
            <span>Saat yönünde 180°</span>
          </label>
        </div>
        <div class="col-lg-2">
          <label class="img_radio">
            <input type="radio" name="rotate" value="rotate270" >
            <img src="./dist/img/main-rotate-270.jpg">
            <span>Saat yönünde 270°</span>
          </label>
        </div>
      </div>
    </div>
    <div class="bolum">
      <h3 class="title">5- ÖLÇEKLENDİRME İŞLEMİ</h3>
      <p class="aciklama">Resmi yeniden boyutlandırmak için yüzdelik değerini seçebilirsiniz. (1000x1000 bir resim için %50 seçimi 500x500 bir resim ortaya çıkaracaktır.)</p>
      <div class="range">
        <input class="range_input" type="range" min="1" max="100" step="1" name="scale" role="input-range">
        <div class="range_value"><span>100</span>%</div>
      </div>

    </div>
    <div class="bolum">
      <h3 class="title">5)DOSYA TİPİ</h3>
      <p class="aciklama">Resmin tipini seçiniz.</p>

      
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="type1" name="dosyatipi" value="jpeg"  checked="" class="custom-control-input">
        <label class="custom-control-label" for="type1">JPG</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="type2" name="dosyatipi" value="png" class="custom-control-input">
        <label class="custom-control-label" for="type2">PNG</label>
      </div>
      

    </div>
    <div class="bolum">
      <input type="submit" value="Gönder" class="btn btn-primary ">
    </div>
  </form>
</div>

<script src="./dist/js/jquery-3.4.1.js"></script>
<script src="./dist/js/rangeslider.min.js"></script>
<script>

 jQuery(document).ready(function($) {

   $('.range_input').rangeslider({

    // Feature detection the default is `true`.
    // Set this to `false` if you want to use
    // the polyfill also in Browsers which support
    // the native <input type="range"> element.
    polyfill: false,

    // Default CSS classes
    rangeClass: 'rangeslider',
    disabledClass: 'rangeslider--disabled',
    horizontalClass: 'rangeslider--horizontal',
    verticalClass: 'rangeslider--vertical',
    fillClass: 'rangeslider__fill',
    handleClass: 'rangeslider__handle',

    // Callback function
    onInit: function() {
      console.log('adasd');
    },

    // Callback function
    onSlide: function(position, value) {
      $('.range_value span').text(value);
    },

    // Callback function
    onSlideEnd: function(position, value) {}
});
   $('.range_input').val(100).change();

 });
</script>
</body>
</html>

