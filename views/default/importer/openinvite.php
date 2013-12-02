<?php
include(elgg_get_plugins_path()  . "importer/vendors/OpenInviter/openinviter.php");
$inviter=new OpenInviter();
$oi_services=$inviter->getPlugins();
$site_name =  elgg_get_site_entity()->name;
if (isset($_POST['provider_box'])) 
{
	if (isset($oi_services['email'][$_POST['provider_box']])) $plugType='email';
	elseif (isset($oi_services['social'][$_POST['provider_box']])) $plugType='social';
	else $plugType='';
}
else $plugType = '';
function ers($ers)
	{
	if (!empty($ers))
		{
		$contents="<table cellspacing='0' cellpadding='0' style='border:1px solid red;' align='center'><tr><td valign='middle' style='padding:3px' valign='middle'><div class='elgg-icon elgg-icon-cross'>&nbsp;</div></td><td valign='middle' style='color:red;padding:5px;'>";
		foreach ($ers as $key=>$error)
			$contents.="{$error}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}
	
function oks($oks)
	{
	if (!empty($oks))
		{
		$contents="<table border='0' cellspacing='0' cellpadding='10' style='border:1px solid green;' align='center'><tr><td valign='middle' valign='middle'><div class='elgg-icon elgg-icon-info'>&nbsp;</div></td><td valign='middle' style='color:green;padding:5px;'>	";
		foreach ($oks as $key=>$msg)
			$contents.="{$msg}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}

if (!empty($_POST['step'])) $step=$_POST['step'];
else $step='get_contacts';

$ers=array();$oks=array();$import_ok=false;$done=false;
if ($_SERVER['REQUEST_METHOD']=='POST')
	{
	if ($step=='get_contacts')
		{
		if (empty($_POST['email_box']))
			$ers['email']= elgg_echo('importer:email_missing');
		if (empty($_POST['password_box']))
			$ers['password']=elgg_echo('importer:password_missing');
		if (empty($_POST['provider_box']))
			$ers['provider']=elgg_echo('importer:email_provider_missing');
		if (count($ers)==0)
			{
			$inviter->startPlugin($_POST['provider_box']);
			$internal=$inviter->getInternalError();
			if ($internal)
				$ers['inviter']=$internal;
			elseif (!$inviter->login($_POST['email_box'],$_POST['password_box']))
				{
				$internal=$inviter->getInternalError();
				$ers['login']=($internal?$internal:elgg_echo('importer:login_failed'));
				}
			elseif (false===$contacts=$inviter->getMyContacts())
				$ers['contacts']=elgg_echo('importer:unable_get_contacts');
			else
				{
				$import_ok=true;
				$step='send_invites';
				$_POST['oi_session_id']=$inviter->plugin->getSessionID();
				$_POST['message_box']='';
				}
			}
		}
	elseif ($step=='send_invites')
		{
		if (empty($_POST['provider_box'])) $ers['provider']=elgg_echo('importer:email_provider_missing');
		else
			{
			$inviter->startPlugin($_POST['provider_box']);
			$internal=$inviter->getInternalError();
			if ($internal) $ers['internal']=$internal;
			else
				{
				if (empty($_POST['email_box'])) $ers['inviter']=elgg_echo('importer:inviter_missing');
				if (empty($_POST['oi_session_id'])) $ers['session_id']=elgg_echo('importer:no_session');
				if (empty($_POST['message_box'])) $ers['message_body']=elgg_echo('importer:message_missing');
				else $_POST['message_box']=strip_tags($_POST['message_box']);
				$selected_contacts=array();$contacts=array();
				$message=array('subject'=>$inviter->settings['message_subject'],'body'=>$inviter->settings['message_body'],'attachment'=>" \n \r \n \r " . elgg_echo('importer:personal_note') . " \n \r \n \r".$_POST['message_box']);
				if ($inviter->showContacts())
					{
					foreach ($_POST as $key=>$val)
						if (strpos($key,'check_')!==false)
							$selected_contacts[$_POST['email_'.$val]]=$_POST['name_'.$val];
						elseif (strpos($key,'email_')!==false)
							{
							$temp=explode('_',$key);$counter=$temp[1];
							if (is_numeric($temp[1])) $contacts[$val]=$_POST['name_'.$temp[1]];
							}
					if (count($selected_contacts)==0) $ers['contacts']= elgg_echo('importer:no_invite_contacts');
					}
				}
			}
		if (count($ers)==0)
			{
			$sendMessage=$inviter->sendMessage($_POST['oi_session_id'],$message,$selected_contacts);
			$inviter->logout();
			if ($sendMessage===-1)
				{
				$message_footer=" \r \n \r \n " . elgg_echo('importer:footer', array($site_name));
				$message_subject=$_POST['email_box'].$message['subject'];
				$message_body=$message['body'].$message['attachment'].$message_footer; 
				$headers="From: {$_POST['email_box']}";
				foreach ($selected_contacts as $email=>$name)
					mail($email,$message_subject,$message_body,$headers);
				$oks['mails']=elgg_echo('importer:sent_success');
				}
			elseif ($sendMessage===false)
				{
				$internal=$inviter->getInternalError();
				$ers['internal']=($internal?$internal:elgg_echo('importer:error_sending'));
				}
			else $oks['internal']=elgg_echo('importer:sent_success');
			$done=true;
			}
		}
	}
else
	{
	$_POST['email_box']='';
	$_POST['password_box']='';
	$_POST['provider_box']='';
	}

$contents="<script type='text/javascript'>
	function toggleAll(element) 
	{
	var form = document.forms.openinviter, z = 0;
	for(z=0; z<form.length;z++)
		{
		if(form[z].type == 'checkbox')
			form[z].checked = element.checked;
	   	}
	}
</script>";
$contents.="<form action='' method='POST' name='openinviter'>"."<div class=\"open-invite-ers-ok\">".ers($ers).oks($oks)."</div>";
if (!$done)
	{
	if ($step=='get_contacts')
		{
        $contents.='<div class="open-invite-login">';		    
		$contents.="<table align='center' class='thTable' cellspacing='2' cellpadding='0' style='border:none;'>
		<tr class='thTableRow'><td align='right'><label for='provider_box'>" . elgg_echo('importer:email_provider') . "</label></td><td><select class='thSelect' name='provider_box'><option value=''></option>";
        foreach ($oi_services as $type=>$providers) 
            {
            $contents.="<optgroup label='{$inviter->pluginTypes[$type]}'>";
            foreach ($providers as $provider=>$details)
                $contents.="<option value='{$provider}'".($_POST['provider_box']==$provider?' selected':'').">{$details['name']}</option>";
            $contents.="</optgroup>";
            }
        $contents.="</select></td></tr>
			<tr class='thTableRow'><td align='right'><label for='email_box'>" . elgg_echo('importer:email') . "</label></td><td><input class='thTextbox' type='text' name='email_box' value='{$_POST['email_box']}'></td></tr>
			<tr class='thTableRow'><td align='right'><label for='password_box'>" . elgg_echo('importer:password') . "</label></td><td><input class='thTextbox' type='password' name='password_box' value='{$_POST['password_box']}'></td></tr>
			
			<tr class='thTableImportantRow'><td colspan='2' align='center'><br/><input class='thButton elgg-button elgg-button-submit' type='submit' name='import' value='" . elgg_echo('importer:address_book') . "'></td></tr>
		</table><input type='hidden' name='step' value='get_contacts'>";
        $contents.='</div>';
		}
	else
	{
        $contents.='<div class="open-invite-note">';
		$contents.="<table class='thTable' cellspacing='0' cellpadding='0' style='border:none;'>
				<tr class='thTableRow'><td align='right' valign='top'><label for='message_box'>" . elgg_echo('importer:personal_note') . "</label></td><td><textarea rows='5' cols='50' name='message_box' class='thTextArea' style='width:600px;'>{$_POST['message_box']}</textarea></td></tr>
			
			</table>";
    }
	}
if (!$done)
	{
	if ($step=='send_invites')
		{
		if ($inviter->showContacts())
			{
			$contents.='<div class="open-invite-addresses">';
			$contents.="<table class='thTable' align='center' cellspacing='0' cellpadding='0'><tr class='thTableHeader'><td colspan='".($plugType=='email'? "3":"2")."'>" . elgg_echo('importer:your_contacts') . "</td></tr>";
			if (count($contacts)==0)
				$contents.="<tr class='thTableOddRow'><td align='center' style='padding:20px;' colspan='".($plugType=='email'? "3":"2")."'>" . elgg_echo('importer:no_contacts') . "</td></tr>";
			else
				{
				$contents.="<tr class='thTableDesc'><td><input type='checkbox' onChange='toggleAll(this)' name='toggle_all' title='Select/Deselect all' checked>" . elgg_echo('importer:invite') . "</td><td>" . elgg_echo('importer:name') . "</td>".($plugType == 'email' ?"<td>" . elgg_echo('importer:email') . "</td>":"")."</tr>";
				$odd=true;$counter=0;
				foreach ($contacts as $email=>$name)
					{
					$counter++;
					if ($odd) $class='thTableOddRow'; else $class='thTableEvenRow';
					$contents.="<tr class='{$class}'><td><input name='check_{$counter}' value='{$counter}' type='checkbox' class='thCheckbox' checked><input type='hidden' name='email_{$counter}' value='{$email}'><input type='hidden' name='name_{$counter}' value='{$name}'></td><td>{$name}</td>".($plugType == 'email' ?"<td>{$email}</td>":"")."</tr>";
					$odd=!$odd;
					}
				
				}
			$contents.="</table>";
            if (count($contacts)>0)
                $contents.="<div class='open-invite-send'><input type='submit' name='send' value='" . elgg_echo('importer:send_invites') . "' class='thButton elgg-button elgg-button-submit'></div>";
            $contents.='</div>';
			}
		$contents.="<input type='hidden' name='step' value='send_invites'>
			<input type='hidden' name='provider_box' value='{$_POST['provider_box']}'>
			<input type='hidden' name='email_box' value='{$_POST['email_box']}'>
			<input type='hidden' name='oi_session_id' value='{$_POST['oi_session_id']}'>";
		}
	}
$contents.="</form>";
echo $contents;
?>
