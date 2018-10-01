<?php
$htmlFound=0; // Database connection, in case you have to add data to db mysql_connect('localhost','username','password') or die('db error 1'); mysql_select_db('dbname') or die('db error 2');
//Stdin catches the whole text of incoming email puts it into $fd variable
$fd = fopen("php://stdin", "r"); // Read the first two MB of incoming email
$fllg=0; $email = ""; $contents = "";
while (!feof($fd))
{
$contents .= fread($fd, 204800);
if($fllg==0)
{
$email=$contents; $fllg=1;
}
} fclose($fd);
//Add Slashes
$email=addslashes($email);
// Empty variables to avoid errors later on
$from = ""; $replyto = ""; $subject = ""; $headers = ""; $message = ""; $split = true;
// Break the e-mail into a line by line array to be put together again later on.
$lines = explode("\n", $email);

//Start splitting the headers and message from extracted email part.

for ($i=0; $i if ($split) {
// this is a header
$headers.= $lines[$i]."\n";
// look out for special headers
if (preg_match("/^Subject: (.*)/", $lines[$i], $matches))
{
$subject = $matches[1];
}
if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
$from = $matches[1];
}
if (preg_match("/^Reply-To: (.*)/", $lines[$i], $matches))
{
$replyto = $matches[1];
}
}
else
{
$htmo=strpos($lines[$i],"Content-Type: text/html;");
if($htmo===false) //Plain text part is still being processed
{}
else
{
$htmlFound=1;
}//If in multipart message HTML section is started then set the varable to 1
$su=$subject[0];
$su.=$subject[1];
$su.=$subject[2];
$currLine=$lines[$i];
if($su=="RE:"||$su=="Re:")//check for repliess — dont show origional messages in replies.
{
//Same $htmlFound variable here is used to stop fetching the original message from a reply if($currLine[0]==">")
{
$htmlFound=1;
}
if($currLine=="—- Original Message —–\n")
{
$htmlFound=1;
}

if($currLine=="—- Original Message —–")
{
$htmlFound=1;
}
}

if($htmlFound==0)
{ // not a header, but message
$message.=$lines[$i]."\n";
}
} if (trim($lines[$i])=="") { // empty line, header section has ended $split = false; } }

// Get the current time to keep the database on a standard time listing
$time = time();

//Pick out the sender's email without any extra characters just simple email which can be reused to put in db or send a reply etc
if(strpos($from, "<"))
{
$from_email = substr($from, strpos($from, "<")+1, strpos($from, ">")-strpos($from, "<")-1);
}
else if(strpos($from, "[mailto:"))
{
$from_email = substr($from, strpos($from, "[mailto:")+8, strpos($from, "]")-strpos($from, "[")-8);
}
else if(strpos($from, "[Mailto:"))
{
$from_email = substr($from, strpos($from, "[Mailto:")+8, strpos($from, "]")-strpos($from, "[")-8);
}
else if(strpos($from, "["))
{
$one=strpos($from, "[")+1;
$two=strpos($from, "]")-strpos($from, "[")-1;
$from_email = substr($from,$one, $two);
}
else if(($from[0]=="[")&&($from[1]=="m") && ($from[2]=="a") && ($from[3]=="i") && ($from[4]=="l") && ($from[5]=="t") && ($from[6]=="o") && ($from[7]==":"))
{
$one=strpos($from, "[mailto")+8;
$two=strpos($from, "]")-strpos($from, "[mailto")-8;
$from_email = substr($from,$one, $two);
}
else if(($from[0]=="[") && ($from[1]=="M") && ($from[2]=="a") && ($from[3]=="i") && ($from[4]=="l") && ($from[5]=="t") && ($from[6]=="o") && ($from[7]==":"))
{
$one=strpos($from, "[Mailto")+8;
$two=strpos($from, "]")-strpos($from, "[Mailto")-8;
$from_email = substr($from,$one, $two);
}
else if(($from[0]=="[") && ($from[1]=="M") && ($from[2]=="a") && ($from[3]=="i") && ($from[4]=="l") && ($from[5]=="t") && ($from[6]=="o") && ($from[7]==":"))
{
$one=strpos($from, "[")+1;
$two=strpos($from, "]")-strpos($from, "[")-1;
$from_email = substr($from,$one, $two);
}
else if($from[0]=="<")
{
$one=strpos($from, "<")+1;
$two=strpos($from, ">")-strpos($from, "<")-1;
$from_email = substr($from,$one, $two);
}
else
{
$from_email=trim($from);
}

/*Variable content discription:
$from_email contains senders email neat and clean without extra characters
$from contains sender's email full line with extra characters, use of variable $from_email is recommended over this
$replyto contains email which can be used to reply sender, still use of $from_email is recomended
$subject contains subject of incoming email
$headers contain full header text
$message contains the email message
$time contains the current date/time info, which can be saved in your favorite/desired format.

*****************Optional log file code*********************
<?
	$email = file_get_contents('php://stdin');
	$handle = fopen("C:\\temp\\output.txt", "a");
	fwrite($handle, date("Y-m-d H:i:s"));
	fwrite($handle, "\r\n");
	fwrite($handle, $email);
	fwrite($handle, "\r\n");
	fclose($handle);
?>
********************END log file code***********************

**************Email Pipe script for hmailserver*************
Const g_sPHPPath     = "C:\path\to\php.exe" 
Const g_sScriptPath  = "C:\path\to\script.php" 
Const g_sPipeAddress = "something@yourdomain.com"

const g_sDQ          = """" 

Sub OnDeliverMessage(oMessage) 
   
   If g_sPipeAddress = "" Then
      bPipeMessage = True
   Else
      bPipeMessage = False

      Set obRecipients = oMessage.Recipients
      
      For i = 0 to obRecipients.Count - 1
         Set obRecipient = obRecipients.Item(i)
         
         If LCase(obRecipient.Address) = LCase(g_sPipeAddress) Then
            bPipeMessage = True
         End If
      Next
   End If
      
   If bPipeMessage Then
      sCommandLine = "cmd /c type " & g_sDQ & oMessage.Filename & g_sDQ & " | " & g_sDQ & g_sPHPPath & g_sDQ & " " & g_sDQ & g_sScriptPath & g_sDQ 
      Set oShell = CreateObject("WScript.Shell") 
      Call oShell.Run(sCommandLine, 0, TRUE) 
   End If
End Sub 


******************END pipe script code**********************

*/
