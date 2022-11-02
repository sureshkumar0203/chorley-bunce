<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
/*var onloadCallback = function() {
	grecaptcha.render('html_element', {
    'sitekey' : '6LfZaywUAAAAAPJnNFMo2R508oR3YhZEi834Wvjv'
  });
};
onloadCallback();

$('form').on('submit', function(e) {
  if(grecaptcha.getResponse() == "") {
    e.preventDefault();
    alert("You can't proceed!");
  } else {
    alert("Thank you");
  }
});*/

function captchaCallback(response) {
    alert(response);
	$('#cr').val(response);
	
}
</script>

<script src='https://www.google.com/recaptcha/api.js'></script>
<form action="?" method="POST">
<input type="text" name="cr" id="cr" value="0"/>
<div class="col-xs-12 col-sm-12">
  <label for="reCaptcha" class="label">&nbsp;</label>
  <div class="g-recaptcha" data-sitekey="6LfZaywUAAAAAPJnNFMo2R508oR3YhZEi834Wvjv" data-callback="captchaCallback"></div>
  <div class="help-block">This field is required.</div>
</div>
<input type="submit" value="Submit">
</form>
</body>
</html>