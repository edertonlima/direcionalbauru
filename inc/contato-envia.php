<?php



    //Atenticador do e-mail com SSL
    require('inc/contato/mail.send.php');

    //Armazena se houver um arquivo na variavel
    $file = ($_POST['anexo']['tmp_name'] ? $_POST['anexo'] : null);
    
    //Depois de setar os arquivos, remove do scopo de verificação e libera a memoria
    unset($_POST['g-recaptcha-response'], $_POST['anexo']);

    //Informações que serão gravadas no isereleads
    $recebenome = $_POST["nome"];
    $recebemail = $_POST["email"];
    $recebetelefone = $_POST["telefone"];
    $recebecomo_conheceu = $_POST["como_nos_conheceu"];
    $recebemensagem = strip_tags(trim($_POST["mensagem"]));

    // MENSAGEM 
    $corpo = null;
    $corpo .= "<table style='border-collapse:collapse;border-spacing:0;border-color:#761919'>
              <tr>
                <th style='font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;border-top-width:1px;border-bottom-width:1px;vertical-align:top;text-align: center;' colspan='2'>
                  <a href='{$url}' title='{$nomeSite}'><img src='{$url}/imagens/logo.png' width='300' title='{$nomeSite}' alt='{$nomeSite}'></a>
                </th>
              </tr>
              
              <tr>
                <th style='font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;border-top-width:1px;border-bottom-width:1px;vertical-align:top;text-align: center;' colspan='2'>
                  Mensagem recebida de {$recebenome}, via formulário do site.
                </th>
              </tr>
              
              <tr>";
    foreach ($_POST as $key => $value):
      $corpo .= "<tr>
              <td style='font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;background-color:#f9f9f9;border-top-width:1px;border-bottom-width:1px;vertical-align:top;border-right:1px solid #ccc;'>
                <b>" . strtoupper(str_replace(array('_', '-'), ' ', $key)) . ": </b>
              </td>
              <td style='font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f9f9f9;border-top-width:1px;border-bottom-width:1px;vertical-align:top'>
                {$value}
              </td>
              </tr>";
    endforeach;
    $corpo .= "</tr>   
              <tr>
                <td style='text-align:center;font-family:Arial, sans-serif;font-size:9px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;border-top-width:1px;border-bottom-width:1px;text-align:center;vertical-align:top' colspan='2'>
                  Mensagem automática enviada por - {$nomeSite} em " . date('d/m/Y H:i:s') . "
                </td>
              </tr>
              <tr>
                <td style='text-align:center;font-family:Arial, sans-serif;font-size:9px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;border-top-width:1px;border-bottom-width:1px;text-align:center;vertical-align:top' colspan='2'>
                  <a href='{$url}' title='{$nomeSite}'>{$url}</a>
                </td>
              </tr>
            </table>";
$emailContato = "direcional@direcionalbauru.com.br";
// ENVIO EMPRESA
    $mail->From = $EMAIL; // Remetente
    $mail->FromName = $_POST['nome']; // Remetente nome
    $mail->Sender = $EMAIL; // Seu e-mail

    $mail->AddAddress($emailContato, $EMPRESA); // Destinatário principal
    //Se houver anexo
    if (isset($file) && !empty($file)):
      $mail->AddAttachment($file['tmp_name'], $file['name']); // Anexo
    endif;
    //$mail->AddCC('adm@site.com.br', 'Teste'); // Copia
    //$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
    $mail->AddReplyTo($_POST['email'], $_POST['nome']); // Reply-to
    $mail->Subject = $EMPRESA . ': Contato pelo site'; // Assunto da mensagem
    $mail->Body = $corpo; // corpo da mensagem
    $mail->Send(); // Enviando o e-mail
    $mail->ClearAllRecipients(); // Limpando os destinatários
    $mail->ClearAttachments(); // Limpando anexos
    
    // ENVIO USUÁRIO
    $mail->From = $recebemail; // Remetente
    $mail->FromName = $EMPRESA; // Remetente nome
    $mail->Sender = $EMAIL; // Seu e-mail
    $mail->AddAddress($_POST['email'], $_POST['nome']); // Destinatário principal
    //Se houver anexo
    if (isset($file) && !empty($file)):
      $mail->AddAttachment($file['tmp_name'], $file['name']); // Anexo
    endif;
    $mail->Subject = $EMPRESA . ': Recebemos sua mensagem'; // Assunto da mensagem
    $mail->Body = $corpo; // corpo da mensagem
    $enviaSucesso = $mail->Send(); // Enviando o e-mail
    $mail->ClearAllRecipients(); // Limpando os destinatários
    $mail->ClearAttachments(); // Limpando anexos

  

   if ($enviaSucesso):
     $mensagem = "Enviado com Sucesso";
    
   else:
     $mensagem = "Não Enviado";
     
   endif;
   
echo "<script> alert('$mensagem'); location.href='index.html'; </script>";


