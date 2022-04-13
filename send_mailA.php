<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = strip_tags(trim($_POST["name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $mobile = filter_var(trim($_POST["mobile"]), FILTER_SANITIZE_EMAIL);
       // $message = trim($_POST["message"]);


        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "작성된 양식에 문제가 있습니다. 내용을 확인하시고 다시 접수해 보시기 바랍니다.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.

        $recipient = "hbg199@naver.com";

        // Set the email subject.
        $subject = "문의 - $name";
		$subject = "=?utf-8?B?".base64_encode($subject)."?=\n";

        // Build the email content.
		$email_content = "<html lang='ko'><head><meta charset='utf-8'/></head><body>";
        $email_content .= "이름: $name<br>";
        $email_content .= "핸드폰번호: $mobile<br>";
	$email_content .= "</body></html>";

	$from_name = "=?utf-8?B?".base64_encode($name)."?=\n";

        // Build the email headers.
        $email_headers = "From: $from_name <$email>\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\nContent-Transfer-Encoding: base64\r\n";

        // Send the email.
        if (mail($recipient, $subject, chunk_split(base64_encode($email_content)), $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "문의가 접수되었습니다. 최대한 빠르게 연락드리겠습니다..";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "에러가 발생했습니다. 메세지를 보낼 수 없습니다.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "작성된 양식에 문제가 있습니다. 내용을 확인하시고 다시 접수해 보시기 바랍니다.";
    }

?>