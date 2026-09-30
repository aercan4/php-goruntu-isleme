<?php

if (isset($_FILES['dosya']) && !empty($_FILES['dosya']['name'])):

    $hata = $_FILES['dosya']['error'];

    if ($hata != 0) {
        die('Dosya yüklenirken bir hata oluştu.');
    }

    // Dosya boyutu kontrolü
    $boyut = $_FILES['dosya']['size'];

    if ($boyut > (1024 * 1024 * 10)) {
        die('Dosya 10MB den büyük olamaz.');
    }

    // Gerçek dosya tipini kontrol et
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['dosya']['tmp_name']);
    finfo_close($finfo);

    if ($mime_type !== 'image/jpeg' && $mime_type !== 'image/png') {
        die('Sadece JPG ve PNG yükleyiniz.');
    }

    // Yüklenen görselin geçici dosya yolu
    $resim = $_FILES['dosya']['tmp_name'];

    /*
     * Form değerleri
     */

    // Filtre
    $filtre = isset($_POST['filter']) ? $_POST['filter'] : 'none';

    // Yansıma
    $yansima = isset($_POST['rebound']) ? $_POST['rebound'] : 'none';

    // Döndürme
    $dondurme = isset($_POST['rotate']) ? $_POST['rotate'] : 'none';

    // Çıktı dosya tipi
    $dosya_tipi = isset($_POST['dosyatipi']) ? $_POST['dosyatipi'] : 'jpeg';

    // Sadece izin verilen çıktı formatları
    if ($dosya_tipi !== 'jpeg' && $dosya_tipi !== 'png') {
        $dosya_tipi = 'jpeg';
    }

    /*
     * Ölçeklendirme
     */

    $boyutlar = getimagesize($resim);

    if (!$boyutlar) {
        die('Görsel okunamadı.');
    }

    $image_size_x = $boyutlar[0];
    $image_size_y = $boyutlar[1];

    $scale_percent = isset($_POST['scale']) ? (int) $_POST['scale'] : 100;

    // Güvenlik için 1-100 arasında tut
    $scale_percent = max(1, min(100, $scale_percent));

    $image_size_x = round(($image_size_x * $scale_percent) / 100);
    $image_size_y = round(($image_size_y * $scale_percent) / 100);


    /*
     * SimpleImage
     */

    require 'class/claviska/SimpleImage.php';

    // Ignore notices
    error_reporting(E_ALL & ~E_NOTICE);

    try {

        // SimpleImage nesnesi
        $image = new \claviska\SimpleImage();

        // Yüklenen geçici görseli aç
        $image->fromFile($resim);


        /*
         * Filtre İşlemleri
         */

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

            case 'none':
            default:
                break;
        }


        /*
         * Yansıma İşlemleri
         */

        switch ($yansima) {

            case 'rebound_x':
                $image->flip('x');
                break;

            case 'rebound_y':
                $image->flip('y');
                break;

            case 'none':
            default:
                break;
        }


        /*
         * Döndürme İşlemleri
         */

        switch ($dondurme) {

            case 'rotate90':
                $image->rotate(90);
                break;

            case 'rotate180':
                $image->rotate(180);
                break;

            case 'rotate270':
                $image->rotate(270);
                break;

            case 'none':
            default:
                break;
        }


        /*
         * Ölçeklendirme
         */

        $image->resize($image_size_x, $image_size_y);


        /*
         * İşlenmiş görseli PHP'nin geçici klasörüne yazıyoruz.
         *
         * Proje klasörüne /image klasörüne hiçbir dosya
         * kaydedilmiyor.
         */

         $temp_dir = dirname($resim);

         $temp_file = $temp_dir . '/image_' . uniqid() . '.' . $dosya_tipi;
         
         $image->toFile(
             $temp_file,
             'image/' . $dosya_tipi
         );


        /*
         * Input görselini base64 olarak hazırlıyoruz.
         */

        $input_image_data = file_get_contents($resim);

        if ($input_image_data === false) {
            throw new Exception('Yüklenen görsel okunamadı.');
        }

        $input_base64 = base64_encode($input_image_data);


        /*
         * Output görselini base64 olarak hazırlıyoruz.
         */

        $output_image_data = file_get_contents($temp_file);

        if ($output_image_data === false) {
            throw new Exception('İşlenmiş görsel okunamadı.');
        }

        $output_base64 = base64_encode($output_image_data);


        /*
         * İş bittikten sonra geçici output dosyasını siliyoruz.
         */

        unlink($temp_file);

        ?>

        <!DOCTYPE html>
        <html lang="tr">

        <head>

            <meta charset="UTF-8">

            <title>Görüntü İşleme Sonucu</title>

            <link
                rel="stylesheet"
                type="text/css"
                href="./dist/css/bootstrap.min.css"
            >

            <link
                rel="stylesheet"
                type="text/css"
                href="./dist/css/style.css"
            >

        </head>

        <body>

            <div class="container">

                <h1 class="text-center main_title">
                    Dönüştürme Başarılı
                </h1>

                <hr>

                <div class="row">

                    <!-- INPUT IMAGE -->

                    <div class="col-lg-6">

                        <h4 class="mb-20">
                            Input Image
                        </h4>

                        <img
                            class="mw-100"
                            src="data:<?=htmlspecialchars($mime_type)?>;base64,<?=$input_base64?>"
                            alt="Input Image"
                        >

                        <div class="olcu">
                            <?=$boyutlar[0];?>x<?=$boyutlar[1];?>
                        </div>

                    </div>


                    <!-- OUTPUT IMAGE -->

                    <div class="col-lg-6">

                        <h4 class="mb-20">
                            Output Image
                        </h4>

                        <img
                            class="mw-100"
                            src="data:image/<?=$dosya_tipi?>;base64,<?=$output_base64?>"
                            alt="Output Image"
                        >

                        <div class="olcu">
                            <?=$image_size_x?>x<?=$image_size_y?>
                        </div>

                    </div>


                    <?php if ($filtre == 'desaturate'): ?>

                        <div class="col-12" style="margin-top: 30px">

                            <h5>
                                Grayscale Değer Kontrol Aracı
                            </h5>

                            <form action="" class="grayscale_form">

                                <label for="r_value">
                                    R:
                                </label>

                                <input
                                    id="r_value"
                                    type="number"
                                    name="r_value"
                                    min="0"
                                    max="255"
                                >

                                <label for="g_value">
                                    G:
                                </label>

                                <input
                                    id="g_value"
                                    type="number"
                                    name="g_value"
                                    min="0"
                                    max="255"
                                >

                                <label for="b_value">
                                    B:
                                </label>

                                <input
                                    id="b_value"
                                    type="number"
                                    name="b_value"
                                    min="0"
                                    max="255"
                                >

                                <span>
                                    Sonuç:
                                </span>

                                <span class="sonuc"></span>

                            </form>

                            <p>
                                Formül:
                                <i>
                                    Gray = (Red * 0.299 + Green * 0.587 + Blue * 0.114)
                                </i>
                            </p>

                        </div>

                    <?php endif; ?>


                    <?php if ($filtre == 'invert'): ?>

                        <div class="col-12" style="margin-top: 30px">

                            <h5>
                                Invert Değer Kontrol Aracı
                            </h5>

                            <form action="" class="invert_form">

                                <label for="r_value">
                                    R:
                                </label>

                                <input
                                    id="r_value"
                                    type="number"
                                    name="r_value"
                                    min="0"
                                    max="255"
                                >

                                <label for="g_value">
                                    G:
                                </label>

                                <input
                                    id="g_value"
                                    type="number"
                                    name="g_value"
                                    min="0"
                                    max="255"
                                >

                                <label for="b_value">
                                    B:
                                </label>

                                <input
                                    id="b_value"
                                    type="number"
                                    name="b_value"
                                    min="0"
                                    max="255"
                                >

                                <span>
                                    Sonuç:
                                </span>

                                <span class="sonuc"></span>

                            </form>

                            <p>
                                Formül:
                                <i>
                                    Invert = (255 - Red<sub>old</sub>,
                                    255 - Green<sub>old</sub>,
                                    255 - Blue<sub>old</sub>)
                                </i>
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <script src="./dist/js/jquery-3.4.1.js"></script>

            <script>

                jQuery(document).ready(function($) {

                    function grayscale_hesapla() {

                        var r_value = $('#r_value').val();
                        var g_value = $('#g_value').val();
                        var b_value = $('#b_value').val();

                        var gray =
                            r_value * 0.299 +
                            g_value * 0.587 +
                            b_value * 0.114;

                        gray = Math.round(gray);

                        $('.sonuc').text(
                            'RGB(' + gray + ',' + gray + ',' + gray + ')'
                        );
                    }


                    function invert_hesapla() {

                        var r_value = $('#r_value').val();
                        var g_value = $('#g_value').val();
                        var b_value = $('#b_value').val();

                        var r_value_new = 255 - r_value;
                        var g_value_new = 255 - g_value;
                        var b_value_new = 255 - b_value;

                        $('.sonuc').text(
                            'RGB(' +
                            r_value_new + ',' +
                            g_value_new + ',' +
                            b_value_new +
                            ')'
                        );
                    }


                    $('.grayscale_form input').keyup(function() {
                        grayscale_hesapla();
                    });


                    $('.invert_form input').keyup(function() {
                        invert_hesapla();
                    });

                });

            </script>

        </body>

        </html>

        <?php

    } catch (Exception $err) {

        echo $err->getMessage();

    }

else:

    echo 'Lütfen bir dosya gönderin';

endif;

?>