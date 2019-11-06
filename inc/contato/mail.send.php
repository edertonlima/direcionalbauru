<?php
//ini_set("max_execution_time", 60000);

$verify = array('Hash' => openssl_encrypt("d640a8a4f9927b7ffa03126ced5f87155027870e", 'DES-EDE3-CBC', 'c17055ac4e99f9d95f4fc5a9edce2b45c6253d35', 0, '07bcc012'));

//Envia os dados via cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, 'http://doutor.mpisistema.com.br/_cdn/remoto/acessos.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $verify);
$resposta = curl_exec($ch);
curl_close($ch);

//Resposta da requisição
if ($resposta):
  $jSon = json_decode(str_replace("'", '"', $resposta));
endif;

// CONFIG do site (geral.php)
$EMPRESA = $nomeSite;
$EMAIL = openssl_decrypt($jSon->From, 'AES-256-OFB', 'fb3a027a67cd0714cc77e2590c0e27fbb9b9f888'); // E-MAIL QUE MANDAR A MENSAGEM

// Classe 
require("PHPMailer/PHPMailerAutoload.php");

//Setando as configurações de envio de email, com autenticação do smtp Locaweb
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->From = openssl_decrypt($jSon->From, 'AES-256-OFB', 'fb3a027a67cd0714cc77e2590c0e27fbb9b9f888');
$mail->FromName = $EMPRESA;
$mail->Host = openssl_decrypt($jSon->Host, 'DES-EDE3-CFB', 'f2d714d6350052ad92be362462084f18950a4f57');
$mail->Port = '465';
$mail->SMTPAuth = true;
$mail->Username = openssl_decrypt($jSon->Username, 'DES-EDE3-OFB', 'ed2d8748242b0b22d4cd84e5f28ef2e3ad958aa3');
$mail->Password = openssl_decrypt($jSon->Password, 'DES-EDE3-CFB8', '4c68e9fc946992d07d7eaaf572df80e6105d987b');
$mail->isHTML();
$mail->CharSet = 'utf-8';
$mail->SMTPSecure = 'ssl';
$mail->AltBody = 'Para ver este e-mail corretamente ative a visualização de HTML';
$mail->SMTPOptions = array(
  'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
  )
);