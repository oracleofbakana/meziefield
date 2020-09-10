<?php

require("mezie_db.php");


require ("PHPMailer.php");
require ("SMTP.php");
require ("Exception.php");


class Mezie{
		var $con;
		private $result, $mailresult;

public function __construct(){
		global $db_host,$db_name, $db_username, $db_password;
		try {
			$this->con = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
		}
		catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}
		return $this->con;
	}
	
	
	public function close_connection(){
		if(isset($this->con)){
		$this->con = NULL;
		//unset($this->con);
		}
	}
		 
	
	public function test_here($txt="", $mode=0){
		if($mode!=0){
		echo "
		<script>
		alert(\"$txt\");
		</script>
		";
		}
		else{ echo "$txt"; }
		exit();
	}
	
	public function just_notify($msg,$mode=1){
		if($mode==1){
		$msg = "    
		<div class='alert alert-success' role = 'alert'>
		<a href='#' class='close' data-dismiss='alert'>&times;</a>
        $msg
		</div>";
			}
		else{
		$msg = "<div class='alert alert-danger' role = 'alert'>
		<a href='#' class='close' data-dismiss='alert'>&times;</a>
        $msg 
		</div>";

		}
		return $msg;
	}
	
	public function just_close_notify($msg,$mode=1){
		if($mode==1){
		$msg = "    
		<div class='alert alert-success fade in out'>
		<a href='#' class='close' data-dismiss='alert'></a>
        $msg
		</div>";
			}
		else{
		$msg = "<div class='alert alert-danger fade in out'>
		<a href='#' class='close' data-dismiss='alert'></a>
        $msg 
		</div>";

		}
		return $msg;
	}
	
	public function test_form($strict=0){
	    $exception = array("qualific","contaddr","kinaddress");
	    $pattern = ($strict==1)?"/[^A-Za-z0-9\ \_]/":"/[^A-Za-z0-9\ \.\,\;\_\-\+\'\&\@\/]/";
		foreach($_POST as $k => $v){
		if(in_array($k,$exception)){continue;}
		if(is_array($v)){
			foreach($v as $i => $j){
			if(preg_match($pattern, $j)){
			$this->goto_notify(1,"$j Contains Invalid Characters");
			}
			}
		}
		else{
			if(preg_match($pattern, $v)){
			$this->goto_notify(1,"$k Contains Invalid Characters");
			}
			}
		}
	}
	
	public function test_array($assoc_array){
		foreach($assoc_array as $k => $v){
		if(is_array($v)){
			foreach($v as $i => $j){
			echo "$k=> $i  =  $j <br>";
			}
		}else{
		echo "$k  =  $v <br>";
		}
		exit();
		}
	}
	
	public function test_user_exist($id){
		if($this->get_this_data("username","exam_users","username",$id)==""){
				return $this->just_notify("Staff Number not found. Add this staff before granting access to the system",2);
		}	
	}
	
	public function return_data($condition){
		try{
				$data = "";
				$sql = "SELECT ".$condition;
				$stmt = $this->con->prepare($sql);
				$stmt->execute();
				$data = $stmt->fetchColumn();
				/* while($row = $stmt->fetch(PDO::FETCH_ASSOC))
				{  
					$data = $row[]; 	
				}  */ 
				return $data;
		}
		catch(PDOException $exception){
				echo "Connection error: " . $exception->getMessage();
		}		
	}
	
	public function run_and_return_array($sql,$returnfield){
	try{
		if(strtoupper(substr($sql,0,6))!="SELECT"){
			$this->goto_notify(1,"Fatal Error!");
		}
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		 /* if(!$stmt->execute()){
			die('Encountered Error []');
		}  */
		$thislist = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$thislist[] = $row["$returnfield"];
		}
		return $thislist;
	}
	catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
	}
	}//End of run_and_return_array function
	
	
	public function wordify($word,$type=0){
		if($type==0) return htmlspecialchars(trim(ucwords(strtolower($word))));
		if($type==5) return htmlspecialchars(trim(ucwords(strtoupper($word))));
		else return htmlspecialchars(trim($word));
	}	
		
	public function printify($word,$type=0){
		if($type==0) return html_entity_decode(trim(ucwords(stripslashes(strtolower($word)))));
		if($type==5) return html_entity_decode(strtoupper(trim(stripslashes($word))));
		else return trim(stripslashes($word));
	}
	
	public function goto_notify($addr="", $msg=""){
		if($addr!="" && $msg !=""){
		$addr = ($addr==1)? "javascript:history.back(1)":$addr;
		echo "
		<script>
		alert(\"$msg\");
		window.location = \"$addr\";
		</script>
		";
		}elseif($addr!=""){
		$addr = ($addr==1)? "javascript:history.back(1)":$addr;
		echo "
		<script>
		window.location = \"$addr\";
		</script>
		";
		}
		else{
		echo "
		<script>
		alert(\"$msg\");
		</script>
		";
		}
		exit();
	}

	public function get_this_data($targetfield,$fromtable,$sourcefield,$keyword){
	    try{
			$data = "";
			$sql = "SELECT $targetfield FROM $fromtable WHERE $sourcefield = :sourcefield LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam(':sourcefield', $keyword); 
			$stmt->execute();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{  
				$data = $row[$targetfield]; 	
			}  
			return $data;
	    }
	    catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
	    }		
	
	}
	
	public function get_data_with_two_condition($targetfield,$fromtable,$sourcefield,$keyword,$sourcefield2, $keyword2){
	try{
			$data = "";
			$sql = "SELECT $targetfield FROM $fromtable WHERE $sourcefield = :keyword AND $sourcefield2 = :keyword2 LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam(':keyword', $keyword); 
			$stmt->bindParam(':keyword2', $keyword2); 
			$stmt->execute();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{  
				$data = ucwords($row[$targetfield]); 	
			} 
			return $data;
		}
		catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}		
	}
	
	public function modal_notify($heading,$message){
			echo "<div class='modal fade' id='memberModal' tabindex='-1' role='dialog' aria-labelledby='memberModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
										<h4 class='modal-title' id='memberModalLabel'>$heading</h4>
								</div>
								<div class='modal-body'>
										<p>$message</p>
								</div>
								<div class='modal-footer'>
										<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
								</div>
						</div>
					 </div>
				</div>";
	}
	
	
	public function count_sql($sqltocount){
	try{
			$result="";
			if($sqltocount != ""){
			$sql = "SELECT COUNT(*) " . $sqltocount;
			$result = $this->con->prepare($sql);
			$result->execute();
			$row = $result->fetchColumn();
			return $row;
			}
		}catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}		
	} 
	
	public function update_this_data($table,$updatefield,$updatevalue,$sourcefield,$keyword){
			try{
					$data = "";
					$sql = "UPDATE $table SET `$updatefield` = :updatefield WHERE `$sourcefield`= :sourcefield";
					
					$stmt = $this->con->prepare($sql);
					$stmt->bindParam(':updatefield', $this->wordify($updatefield));
					$stmt->bindParam(':sourcefield', $this->wordify($sourcefield));
					if($stmt->execute()){
						return $this->just_notify("passed ",2);
					}
					else{
						return $this->just_notify("failed ");
					}
					
				}
				catch(PDOException $exception){
						echo "Connection error: " . $exception->getMessage();
				}
	}
	
	public function update_this_data1($table,$updatefield,$updatevalue,$sourcefield,$keyword){
			try{
					$data = "";
					$sql = "UPDATE $table SET `$updatefield` = :updatefield WHERE `$sourcefield`= :sourcefield";
					$stmt = $this->con->prepare($sql);
					$stmt->bindParam(':updatefield', $this->wordify($updatevalue));
					$stmt->bindParam(':sourcefield', $this->wordify($keyword));
					if($stmt->execute()){
						return $this->just_notify("passed ",2);
					}
					else{

						return $this->just_notify("failed ");
					}
					
				}
				catch(PDOException $exception){
						echo "Connection error: " . $exception->getMessage();
				}
	}
	
	public function update_two_data($table,$targetfield,$targetvalue,$targetfield2,$targetvalue2,$sourcefield,$keyword){
			$data = "";
			$sql = "UPDATE $table SET $targetfield  = '$targetvalue', 
			$targetfield2 = '$targetvalue2' WHERE $sourcefield ='$keyword'";
			if(!$result = $this->con->query($sql))
			{
			$this->goto_notify(1,'Error while updating data '.$this->con->error);
			}
			else{
			$this->goto_notify(1,'UPDATE SUCCESFUL '.$this->con->error);
	}
	}
	
	public function date_to_text($date){
		$explode_date						=			explode("-", $date);
		$year								=			$explode_date[0];
		$month								=			$explode_date[1];
		$day								=			$explode_date[2];
		switch($month){
			case 01:	
						$month = "January";
						break;
			case 02:	
						$month = "February";
						break;
			case 03:	
						$month = "March";
						break;
			case 04:	
						$month = "April";
						break;
			case 05:	
						$month = "May";
						break;
			case 06:	
						$month = "June";
						break;
			case 07:	
						$month = "July";
						break;
			case '08':	
						$month = "August";
						break;
			case '09':	
						$month = "September";
						break;
			case 10:	
						$month = "October";
						break;
			case 11:	
						$month = "November";
						break;
			case 12:	
						$month = "December";
						break;
			default:
						$month = "";
						break;
		}
		
		$date = implode(" ",array($month,$day));
		$date = implode(",",array($date,$year));
		return $date;
	}
	
	public function br2newline( $input ) {
		 $out = str_replace( "<br>", "\n", $input );
		 $out = str_replace( "<br/>", "\n", $out );
		 $out = str_replace( "<br />", "\n", $out );
		 $out = str_replace( "<BR>", "\n", $out );
		 $out = str_replace( "<BR/>", "\n", $out );
		 $out = str_replace( "<BR />", "\n", $out );
		 $out = str_replace( "rn", "<br>", $out );
		 $out = str_replace( "\r\n", "<br>", $out );
		 return $out;
	}
	
	public function quotes_replace($input) {
		 $out = str_replace( '"', "'", $input );
		 $out = str_replace( '&ldquo;', "&lsquo;", $input );
		 $out = str_replace( '&rdquo;', "&rsquo;", $input );
		 //$out = str_replace( '"', "'", $input );
		 return $out;
	}

	//MAIN FUNCTION BEGINS HERE
	
	public function sendmail($from, $to, $to_name, $subject,$message,$altbody){
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		//$mail = new PHPMailer(true);
		try {
			//Server settings
		//	$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);
			$mail->Host       = 'localhost';               // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'hello@heliumwriters.com';                             // SMTP username
			$mail->Password   = 'Helium@May@2020';                       // SMTP password
			//$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
			$mail->Port       = 25;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($from, 'Helium Writers Team');
			$mail->addAddress($to, $to_name);     				// Add a recipient
			$mail->addReplyTo('hello@heliumwriters.com', 'William Dapper');
			$mail->addCC('dkw.dapper@gmail.com');
		
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $mail->msgHTML($message);
			$mail->AltBody = $altbody;
			//echo $to; exit();
			if(!$mail->send()) {
                      //return 0;
            } else {
                        //return 1;
            }
			
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
}

	
    public function ContactUs(){
        try{
                if (isset($_POST['contact_form'])){
				$name							    = $this->wordify($_POST['name'],0);
				$email							    = $this->wordify($_POST['email'],1);
                $subject							= $this->wordify($_POST['subject'],1);
                //$phone							    = $this->wordify($_POST['phone']);
                $message							= $this->wordify($_POST['message'], 1);
                $time_contacted                     = Date('Y-m-d h:m:i');
                
                $query = 	"insert into mezie_contact SET
                        customer_name				=	:customer_name,
                        customer_email				=	:customer_email,
                       
                        subject     				=	:subject,
                        customer_message			=	:customer_message,
                      	time_contacted 				= 	:time_contacted";
				//echo $query; exit();
				// prepare query for execution
				$stmt = $this->con->prepare($query);
				$stmt->bindParam(':customer_name', $name);		
				$stmt->bindParam(':customer_email', $email);		
				//$stmt->bindParam(':customer_phone', $phone);		
                $stmt->bindParam(':subject', $subject);	
                $stmt->bindParam(':customer_message', $message);	
                $stmt->bindParam(':time_contacted', $time_contacted);	


                if(!$stmt->execute()){
					return $this->just_notify("Oops! There's an error.",2);
				}
				else{
					//echo 'Yes'; exit();
					$from = "hello@heliumwriters.com";
					$to = "fiafiam20@gmail.com";
					$subject = $subject;
					$altbody			 		= 			"Open with HTML Browser";

					$message = "
					<html>
					<body>
					   <p>
					    <h3><strong>Hi, MezieField Investment Service Limited!</strong></h3></br></br>
                               
                                <strong>$name </strong> has sent a message from your contact form $subject<br/><br/>
                               
								Below are their details and inquiry:<br/>
								Full Name:		$name<br/>

								Email Address: 		$email<br/>

								Subject:			$subject<br/>

								Message: 			$message<br/>

								Do well to contact the client on the next step to follow. <br/><br/>
                               
                                Sincerely,<br/>
                                <strong>MezieField Team</strong><br/>
                                http://www.meziefield.com
					</body>
					</html>";
					$headers = "From: $from\n";
					$headers .= "MIME-Version: 1.0\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\n";
                    // SEND EMAIL
			 		$this->sendmail($from, $to, $name, $subject,$message,$altbody);
					return $this->just_notify("Thank you for contacting Helium Writers. We would get in touch with you shortly.");
				}
            }

        }
        catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}

    }

	public function sendbulkmail($from, $subject,$message,$altbody){
	/* 	$sql = "SELECT fullname, email from bd_trainings";
		$stmt = $this->con->prepare($sql);
		$stmt->execute();
		$fullname = $email = array();
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$fullname[] = $this->wordify($row['fullname']);
				$email[]	= $this->wordify($row['email']);
		} */
		
		$email = array("fiafiam20@gmail.com",  "bonjourdapper@gmail.com");

		$fullname = array("Kroye Dapper","DKW Dapper","Bonjour Dapper","Fiafia","Micah Fiafia","Fab-eme Williams");
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		try {
			//Server settings
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'localhost';               				// Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'trainings@bonjourdapper.com';         // SMTP username
			$mail->Password   = 'Bonjour&Dapper@1991#';                // SMTP password
			$mail->Port       = 25;     								// TCP port to connect to

		//	$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                                
			//Recipients
			$mail->setFrom($from, 'MezieField Team');

			//$addresses = explode(',', $email);
    		foreach ($email as $index=> $to_address) {
     		   $mail->AddAddress($to_address);
     		  // echo trim($to_address);
     		  // echo "<br>";
     		  // echo $fullname[$index];  echo "<br>";
    		}
			//$mail->addAddress($to, $to_name);     				// Add a recipient
			$mail->addReplyTo('fiafiam20@gmail.com', 'MezieField Investment Service Limited');
		//	$mail->addCC('bonjourdapper@gmail.com');
			//$mail->addBCC('fiafiam20@gmail.com');
		
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $mail->msgHTML($message);
			$mail->AltBody = $altbody;
			//echo $to; exit();
			if(!$mail->send()) {
                      //return 0;
            } else {
                        //return 1;
            }
			
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

		
	}
}


$bon = new Mezie;

