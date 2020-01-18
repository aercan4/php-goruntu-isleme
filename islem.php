<?php 
if(isset($_FILES['dosya']) && !empty($_FILES['dosya']['name'])):

 $hata = $_FILES['dosya']['error'];
if($hata != 0) {
  die('dosyanız 3mb dan büyük olamaz.');
} else {
  $boyut = $_FILES['dosya']['size'];
  if($boyut > (1024*1024*10)){
    die('Dosya 10MB den büyük olamaz.');
  } else {
   if($_FILES['dosya']['type'] != 'image/jpeg' && $_FILES['dosya']['type'] != 'image/png'){
     die('Sadece Jpg ve Png yükleyiniz');
   }else {
    $resim = $_FILES['dosya']['tmp_name'];

  }
}
}

?>
<?php
//print_r($_POST);
/* Filtre İşlemleri*/
$filtre = $_POST['filter'];

/* Filtre İşlemleri*/
$yansima = $_POST['rebound'];

/* Döndürme İşlemleri*/
$dondurme = $_POST['rotate'];

/* Dosya Tipi*/
$dosya_tipi = $_POST['dosyatipi'];

/* Ölçeklendirme İşlemleri */
$boyutlar = getimagesize($resim);
$image_size_x = $boyutlar[0];
$image_size_y = $boyutlar[1];
$scale_percent = $_POST['scale'];
$image_size_x = ($image_size_x * $scale_percent) / 100;
$image_size_y = ($image_size_y * $scale_percent) / 100;



?>
<?php

require 'class/claviska/SimpleImage.php';

// Ignore notices
error_reporting(E_ALL & ~E_NOTICE);

try {

  // SimpleImage Sınıfından yeni bir nesne oluşturuyoruz.
  $image = new \claviska\SimpleImage();

  // Resmi Seçiyoruz.
  $image->fromFile($resim);

  /* Filtre İşlemleri */
  switch ($filtre) {
   case 'desaturate':
   $image->desaturate();
   break;
   case 'emboss':
   $image->emboss();
   break;
   case 'edgedetect':
   $image->edgedetect();
   break;
   case 'invert':
   $image->invert();
   break;
   case 'pixelate':
   $image->pixelate(50);
   break;
   case 'sepia':
   $image->sepia();
   break;
   case 'canny':
   $image->edgedetect();
  $image->darken(50);
   break;
   case 'sharpen':
    $image->sharpen(70);
     break;
   default:
     # code...
   break;
 }

 // Yansıma İşlemleri

 switch ($yansima) {

   case 'rebound_x':
   $image->flip('x') ;                         
   break;

   case 'rebound_y':
   $image->flip('y');                          
   break;

   default:
     # code...
   break;
 }

  // Döndürme İşlemleri

 switch ($dondurme) {

   case 'rotate90':
   $image->rotate('90');                         
   break;

   case 'rotate180':
   $image->rotate('180');                         
   break;

   case 'rotate270':
   $image->rotate('270');                         
   break;

   default:
     # code...
   break;
 }

 $image->resize($image_size_x,$image_size_y);
 $image->toFile('image'.strtotime("now").'.'.$dosya_tipi, 'image/'.$dosya_tipi); 

 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="UTF-8">
   <title></title>
   <link rel="stylesheet" type="text/css" href="./dist/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="./dist/css/style.css">


 </head>
 <body>
   <div class="container">
     <h1 class="text-center main_title">Dönüştürme Başarılı</h1>
     <hr>
     <div class="row">
      <div class="col-lg-6">
        <h4 class="mb-20">Input Image</h4>
        <?php 
        $imageData = file_get_contents($resim); 

        echo sprintf('<img class="mw-100"  src="data:image/png;base64,%s" />', base64_encode($imageData));
        ?>
        <div class="olcu"><?=$boyutlar[0];?>x<?=$boyutlar[1];?></div>

      </div>
      <div class="col-lg-6">
        <h4 class="mb-20">Output Image</h4>

        <img class="mw-100" src="./image<?=strtotime("now")?>.<?=$dosya_tipi?>" alt="">
        <div class="olcu"><?=$image_size_x?>x<?=$image_size_y?></div>

      </div>
      <?php if($filtre == 'desaturate'): ?>
        <div class="col-12" style="margin-top: 30px">
          <h5>Grayscale Değer Kontrol Aracı</h5>
          <form action="" class="grayscale_form">
           <label for=""> R:  </label>
           <input id="r_value" type="number" name="r_value" >

           <label for="">G: </label>
           <input id="g_value" type="number" name="g_value" >

           <label for="">B: </label>
           <input id="b_value" type="number" name="b_value" >
           <span>Sonuç: </span> <span class="sonuc"></span>
         </form> 

         <p>Formül: <i>Gray = (Red * 0.299 + Green * 0.587 + Blue * 0.114)</i></p>

       </div>

     <?php  endif; ?>
       <?php if($filtre == 'invert'): ?>
        <div class="col-12" style="margin-top: 30px">
          <h5>Invert Değer Kontrol Aracı</h5>
          <form action="" class="invert_form">
           <label for=""> R:  </label>
           <input id="r_value" type="number" name="r_value" >

           <label for="">G: </label>
           <input id="g_value" type="number" name="g_value" >

           <label for="">B: </label>
           <input id="b_value" type="number" name="b_value" >
           <span>Sonuç: </span> <span class="sonuc"></span>
         </form> 

         <p>Formül: <i>Invert = (255 - Red<sub>old</sub> , 255 - Green<sub>old</sub> , 255 - Blue<sub>old</sub> )</i></p>

       </div>

     <?php  endif; ?>
    </div>


  </div>
  <script src="./dist/js/jquery-3.4.1.js"></script>

 <script>
  jQuery(document).ready(function($) {

    function grayscale_hesapla(){
      var r_value = $('#r_value').val();
      var g_value = $('#g_value').val();
      var b_value = $('#b_value').val();

      var gray = r_value*0.299 + g_value*0.587 + b_value *0.114;
      gray = Math.round(gray);
      $('.sonuc').text('RGB('+gray+','+gray+','+gray+')');
    }

    function invert_hesapla(){
      var r_value = $('#r_value').val();
      var g_value = $('#g_value').val();
      var b_value = $('#b_value').val();

      var r_value_new = 255-r_value;
      var g_value_new = 255-g_value;
      var b_value_new = 255-b_value;

      $('.sonuc').text('RGB('+r_value_new+','+g_value_new+','+b_value_new+')');
    }
    $('.grayscale_form input').keyup(function(event) {
      grayscale_hesapla();
    });

    $('.invert_form input').keyup(function(event) {
      invert_hesapla();
    });
  });
</script>
</body>
</html>
<?php


//print_r($image);
} catch(Exception $err) {
  // Handle errors
  echo $err->getMessage();
}

else:
 echo 'Lütfen bir dosya gönderin';

endif;




?>

