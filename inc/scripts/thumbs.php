<?php
/** Developer: -- Rafael Lima & Doutores da Web --
 * @param $BaseDir
 * @param $novaimagem
 * @param null $w
 * @param null $h
 * @param null $zoom
 * @return bool
 *
 * $gerarAgora
 * Esta variavel define se serão geradas as thumbs no diretorio ou se as mesmas serão criadas na hora para cada visualização.
 */
define("GerarAgora", true);

function gerarThumbDoutores($BaseDir, $novaimagem, $w = null, $h = null, $zoom = null)
{
    $Width = (int)$w;
    //Verifica o real mimetype do arquivo
    $type = $novaimagem['mime'];
    $Image = null;

    switch ($type):
        case 'image/jpg':
        case 'image/jpeg':
        case 'image/pjpeg':
            $Image = imagecreatefromjpeg($novaimagem['tmp_name']);
            break;
        case 'image/png':
        case 'image/x-png':
            $Image = imagecreatefrompng($novaimagem['tmp_name']);
            break;
    endswitch;

    if (!$Image):
        return false;
    else:
        $x = imagesx($Image);
        $y = imagesy($Image);

        //Zoom
        if (!empty($zoom)) {
            $align = 'c';
            $percentage = (double)0;

            $new_width = min($Width, 2000);
            $new_height = min($h, 1500);

            $width = imagesx($Image);
            $height = imagesy($Image);
            $origin_x = 0;
            $origin_y = 0;

            if ($new_width && !$new_height) {
                $new_height = floor($height * ($new_width / $width));
            } else if ($new_height && !$new_width) {
                $new_width = floor($width * ($new_height / $height));
            }
            if ($zoom == 3) {
                $final_height = $height * ($new_width / $width);
                if ($final_height > $new_height) {
                    $new_width = $width * ($new_height / $height);
                } else {
                    $new_height = $final_height;
                }
            }

            $NewImage = imagecreatetruecolor($new_width, $new_height);
            imagealphablending($NewImage, false);
            $color = imagecolorallocatealpha($NewImage, 255, 255, 255, 0);

            imagefill($NewImage, 0, 0, $color);

            if ($zoom == 2) {
                $final_height = $height * ($new_width / $width);
                if ($final_height > $new_height) {
                    $origin_x = $new_width / 2;
                    $new_width = $width * ($new_height / $height);
                    $origin_x = round($origin_x - ($new_width / 2));
                } else {
                    $origin_y = $new_height / 2;
                    $new_height = $final_height;
                    $origin_y = round($origin_y - ($new_height / 2));
                }
            }

            imagesavealpha($NewImage, true);
            if ($zoom > 0) {
                if ($percentage < 0) $percentage = 0;
                if ($percentage > 100) $percentage = 100;
                $percentage = $percentage / 100.0;
                $src_x = $src_y = 0;
                $src_w = $width;
                $src_h = $height;
                $cmp_x = $width / $new_width;
                $cmp_y = $height / $new_height;
                if ($cmp_x > $cmp_y) {
                    $src_w = round($width / $cmp_x * $cmp_y);
                    $src_x = round(($width - ($width / $cmp_x * $cmp_y)) / 2);
                } else if ($cmp_y > $cmp_x) {
                    $src_h = round($height / $cmp_y * $cmp_x);
                    $src_y = round(($height - ($height / $cmp_y * $cmp_x)) / 2);
                }
                if ($align) {
                    if (strpos($align, 't') !== false) {
                        $src_y = (int)($percentage * ($height - $src_h));
                    }
                    if (strpos($align, 'b') !== false) {
                        $src_y = (int)((1.0 - $percentage) * ($height - $src_h));
                    }
                    if (strpos($align, 'l') !== false) {
                        $src_x = (int)($percentage * ($width - $src_w));
                    }
                    if (strpos($align, 'r') !== false) {
                        $src_x = (int)((1.0 - $percentage) * ($width - $src_w));
                    }
                }
                imagecopyresampled($NewImage, $Image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);
            } else {
                $wMin = $x / $Width;
                if ($h == 0) {
                    $hMin = $wMin;
                    $h = $y / $wMin;
                } else {
                    $hMin = $y / $h;
                }
                //Crop
                $min = min($wMin, $hMin);
                $xt = $min * $Width;
                $x1 = ($x - $xt) / 2;
                $x2 = $x - $x1;
                $yt = $min * $h;
                $y1 = ($y - $yt) / 2;
                $y2 = $y - $y1;
                $x1 = (int)$x1;
                $x2 = (int)$x2;
                $y1 = (int)$y1;
                $y2 = (int)$y2;

                $NewImage = imagecreatetruecolor($Width, $h);
                imagealphablending($NewImage, false);
                $color = imagecolorallocatealpha($NewImage, 255, 255, 255, 0);
                imagefill($NewImage, 0, 0, $color);
                imagesavealpha($NewImage, true);
                imagecopyresampled($NewImage, $Image, 0, 0, $x1, $y1, $Width, $h, $x2 - $x1, $y2 - $y1);

            }

            switch ($type):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    header("Content-type: " . $type);
                    if (GerarAgora) {
                        imagejpeg($NewImage);
                    } else {
                        imagejpeg($NewImage, $BaseDir . '/' . $novaimagem['name']);
                        readfile($BaseDir . '/' . $novaimagem['name']);
                    }
                    break;
                case 'image/png':
                case 'image/x-png':
                    header("Content-type: " . $type);
                    if (GerarAgora) {
                        imagepng($NewImage);
                    } else {
                        imagepng($NewImage, $BaseDir . '/' . $novaimagem['name']);
                        readfile($BaseDir . '/' . $novaimagem['name']);
                    }
                    break;
            endswitch;
            imagedestroy($Image);
            imagedestroy($NewImage);

        }
    endif;
}

//Pegar dados da imagem
$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$w = (isset($get['w']) && !empty($get['w']) ? $get['w'] : 200);
$h = (isset($get['h']) && !empty($get['h']) ? $get['h'] : 200);
$zoom = (isset($get['zc']) && !empty($get['zc']) ? $get['zc'] : 1);
$imagem = str_replace('/../', '/', (isset($get['src']) ? $get['src'] : $get['imagem']));
$extensao = strtolower(pathinfo($imagem, PATHINFO_EXTENSION));
$nome = pathinfo($imagem, PATHINFO_FILENAME);
$dir = 'thumbs';
$dirImagens = 'imagens';
$permissao = 0755;
$nomeFile = explode('/', $imagem);
$nomeThumb = 'thumb-' . end($nomeFile);

//Se a imagem for encontrada
if ($imagem) {

    if (GerarAgora) {
        $type['mime'] = 'image/' . $extensao;
        $type['tmp_name'] = $imagem;
        gerarThumbDoutores($dirImagens, $type, $w, $h, $zoom);

    } else {

        //Cria o diretorio caso não exista e seta a permissão
        if (!file_exists($dir)) {
            mkdir($dir);
            chmod($dir, $permissao);
        }

        if (!file_exists($dir . '/' . $nomeThumb)) {
            //copiar a imagem
            if (copy($imagem, $dir . '/' . $nomeThumb)) {
                chmod($dir . '/' . $nomeThumb, $permissao);
                $type = getimagesize($dir . '/' . $nomeThumb);
                $type['name'] = $nomeThumb;
                $type['tmp_name'] = $dir . '/' . $nomeThumb;
                $type['size'] = filesize($dir . '/' . $nomeThumb);
                gerarThumbDoutores($dir, $type, $w, $h, $zoom);
            }
        } else {
            //Caso ele exista e tenha sido alterado as dimensões, gerar novamente
            $type = getimagesize($dir . '/' . $nomeThumb);
            if ($get['w'] != $type[0] || $get['h'] != $type[1]) {
                $type['name'] = $nomeThumb;
                $type['tmp_name'] = $dir . '/' . $nomeThumb;
                $type['size'] = filesize($dir . '/' . $nomeThumb);
                gerarThumbDoutores($dir, $type, $w, $h, $zoom);
            }

            //Acessa o diretóri com as thumbs geradas e faz a leitura
            chdir($dir);
            foreach (glob("{*.png,*.jpg,*.jpeg,*.bmp,*.gif}", GLOB_BRACE) as $img):
                if ($nomeThumb == $img) {
                    readfile('../' . $dir . '/' . $img);
                }
            endforeach;
        }
    }

}




