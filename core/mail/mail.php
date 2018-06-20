<?php
if(!defined('IMPARENT')){exit();} // No direct access

define('MAIL_CONTENT',GBL_ROOT_CONTENT.'/mail');

require_once('class.phpmailer.php');
$mail = new PHPMailer();

$mail_options = array();

// Forced the emailer to used PHP Mail if using smtp failed
$mail_options['PHPMailOnFail'] = true;

function setTo($address,$name = ''){
	global $mail_options;
	$mail_options['to'][] = array('address' => $address,'name' => $name);
}

function setFrom($address,$name = '',$reply_to = true){
	global $mail_options;
	$mail_options['from'][] = array('address' => $address,'name' => $name);
	if($reply_to){
		setReplyTo($address,$name);
	}	
}

function setReplyTo($address,$name = ''){
	global $mail_options;
	$mail_options['ReplyTo'][] = array('address' => $address,'name' => $name);
}

function setCc($address,$name = ''){
	global $mail_options;
	$mail_options['cc'][] = array('address' => $address,'name' => $name);
}

function setBcc($address,$name = ''){
	global $mail_options;
	$mail_options['bcc'][] = array('address' => $address,'name' => $name);
}

function setContent($content = ''){
	global $mail_options;	
	if($content == ''){	
		$content = file_get_contents(GBL_ROOT_CORE.'/mail/default_content.html');
		$content = eregi_replace("[\]",'',$content);
	}	
	$mail_options['content'] = $content;
}

function setSubject($subject = ''){
	global $mail_options;
	$mail_options['subject'] = ($subject!='' ? $subject : '(no subject)');
}

function addAttachment($file){
	global $mail_options;
	$mail_options['attachment'][] = $file;
}

function clear(){
	global $mail,$mail_options;
	$mail->ClearAllRecipients();
	unset($mail_options);
}




function __send_mail($type = 'native',$error_display = false){
	global $mail_options,$mail;
	$_from = '';
	$_to = '';
	$_cc = '';
	$_bcc = '';
	
	if(is_array($mail_options)){
		$mail_options = (object) $mail_options;	
		
		//print_r($mail_options);
		
		if($type == 'smtp'){
			$mail->IsSMTP();
			$mail->SMTPAuth   = (MAIL_SMTP_AUTH ? true : false);
			$mail->SMTPSecure = MAIL_SMTP_SECURE;
			$mail->Host       = MAIL_SMTP_HOST;
			$mail->Port       = (MAIL_SMTP_PORT > 0 ? MAIL_SMTP_PORT : 465);
			$mail->Username   = MAIL_SMTP_USERNAME;
			$mail->Password   = MAIL_SMTP_PASSWORD;		
		}
		
		$mail->Subject    = $mail_options->subject;
		$mail->AltBody    = "Errmmm?? You seem to be using non-HTML email viewer!, please look for one so you can view this message.";		
		$mail->AddBCC(MAIL_ADMIN,'Webmaster'); // i should get all the shit!
		
		/* From*/
		for($i = 0;$i <= count($mail_options->from);$i++){
			$from = (object) $mail_options->from[$i];
			if($from->address!=''){
				if($from->name!=''){
					$mail->SetFrom($from->address,$from->name);
					$_from.=($_from!=''?', ' : '').$from->name.' <'.$from->address.'>';
				}else{
					$mail->SetFrom($from->address);
					$_from.=($_from!=''?', ' : '').$from->address;
				}
			}
		}
		
		/* Reply-to*/
		for($i = 0;$i <= count($mail_options->ReplyTo);$i++){
			$replyto = (object) $mail_options->ReplyTo[$i];
			if($replyto->address!=''){
				if($replyto->name!=''){
					$mail->AddReplyTo($replyto->address,$replyto->name);
				}else{
					$mail->AddReplyTo($replyto->address);
				}
			}
		}
		
		/* To */
		for($i = 0;$i <= count($mail_options->to);$i++){
			$to = (object) $mail_options->to[$i];
			if($to->address!=''){
				if($to->name!=''){
					$mail->AddAddress($to->address,$to->name);
					$_to.=($_to!=''?', ' : '').$to->name.' <'.$to->address.'>';
				}else{
					$mail->AddAddress($to->address);
					$_to.=($_to!=''?', ' : '').$to->address;
				}				
			}
		}
		
		/* Bcc */
		for($i = 0;$i <= count($mail_options->bcc);$i++){
			$bcc = (object) $mail_options->bcc[$i];
			if($bcc->address!=''){
				if($bcc->name!=''){
					$mail->AddBCC($bcc->address,$bcc->name);
					$_bcc.=($_bcc!=''?', ' : '').$bcc->name.' <'.$bcc->address.'>';
				}else{
					$mail->AddBCC($bcc->address);
					$_bcc.=($_bcc!=''?', ' : '').$bcc->address;
				}				
			}
		}
		
		/* Cc */
		for($i = 0;$i <= count($mail_options->cc);$i++){
			$cc = (object) $mail_options->cc[$i];
			if($cc->address!=''){
				if($cc->name!=''){
					$mail->AddCC($cc->address,$cc->name);
					$_cc.=($_cc!=''?', ' : '').$cc->name.' <'.$cc->address.'>';
				}else{
					$mail->AddCC($cc->address);
					$_cc.=($_cc!=''?', ' : '').$cc->address;
				}				
			}
		}
		
		/* Attachment */
		for($i = 0;$i <= count($mail_options->attachment);$i++){
			if($mail_options->attachment[$i]!=''){
				$mail->AddAttachment($mail_options->attachment[$i]);
			}
		}
		
		$mail->MsgHTML($mail_options->content);
		
		if(!$mail->Send()){
			if($mail_options->PHPMailOnFail){
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'To: '.$_to."\r\n";
				$headers .= 'From: '.$_from. "\r\n";
				if($_bcc!=''){
					$headers .= 'Bcc: '.$_bcc. "\r\n";
				}
				if($_cc!=''){
					$headers .= 'Cc: '.$_cc. "\r\n";
				}
				
				$headers .= 'Bcc: Webmaster <'.MAIL_ADMIN.'>' . "\r\n"; // As a webmaster i should get all the shit

				// Standard PHP mail
				if(mail($_to, $mail_options->subject,$mail_options->content, $headers)){
					return true;
				}else{
					if($error_display){
						echo 'ERROR: '.$mail->ErrorInfo;
					}else{
						return false;
					}		
				}
			}else{
				if($error_display){
					echo 'ERROR: '.$mail->ErrorInfo;
				}else{
					return false;
				}			
			}
		}else{
			if($error_display){
				echo 'MESSAGE: Message Sent';
			}else{
				return true;
			}
		}
	}else{
		return false;
	}
}
?>