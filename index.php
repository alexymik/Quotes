<?php

if (isset($_SERVER['REDIRECT_URL'])) {
	$args = explode('/', $_SERVER['REDIRECT_URL']);
	array_shift($args);
	array_shift($args);

	$url = array();

	//Parsing the url as parameters
	for ($i = 0; $i < count($args); $i++) {
	    $k = $args[$i];
	    $v = ++$i < count($args) ? $args[$i] : null;
	    $url[$k]= $v;
	}
}

if (isset($_POST) && isset($_POST['quote'])) {

	if ($_POST['quote'] == "") {
		die();
	}
	$quote = "\n".trim($_POST['quote']);

	file_put_contents('quotes.txt', $quote, FILE_APPEND | LOCK_EX);
	
	$quotes = explode("\n", file_get_contents('quotes.txt'));
	echo count($quotes);
	die();
}

$quotes = file_get_contents('quotes.txt');
$quotes = json_decode($quotes, true);
$quotes = $quotes['quotes'];
$numquotes = count($quotes);

function randomQuoteNo() {
	global $numquotes;
	return rand(0, $numquotes - 1);
}

function getQuote($quoteNo) {
	global $quotes;

	$quoteNo = floor($quoteNo);
	
	if (!empty($quoteNo) && is_numeric($quoteNo)) {
		if (is_numeric($quoteNo) && ($quoteNo >= 0) && ($quoteNo < count($quotes))) {
			return json_encode($quotes[$quoteNo]);
		} else {
			return json_encode($quotes[0]);
		}
	} else {
		return json_encode($quotes[0]);
	}
}

if (isset($_GET['q']) && is_numeric($_GET['q']) && $_GET['q'] >= 0 && $_GET['q'] <= $numquotes) {
	die(getQuote($_GET['q']));
} else if (isset($url['q']) && is_numeric($url['q']) && $url['q'] >= 0 && $url['q'] <= $numquotes) {
	die(getQuote($url['q']));
} else {
	if (isset($_GET['quote']) && is_numeric($_GET['quote']) && $_GET['quote'] >= 0 && $_GET['quote'] < $numquotes) {
		$startingQuoteNo = $_GET['quote'];
	} else if (isset($url['quote']) && is_numeric($url['quote']) && $url['quote'] >= 0 && $url['quote'] < $numquotes) {
		$startingQuoteNo = $url['quote'];
	} else {
		$startingQuoteNo = randomQuoteNo();
	}
}
?>

<!doctype html>
<head>
	<meta charset="UTF-8">

	<title>
		Quotes
	</title>

	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="ZeroClipboard.min.js"></script>

	<link rel="stylesheet" type="text/css" href="quotes.css">
	<script type="text/javascript" src="quotes.js"></script>

	<script type="text/javascript">
		var quoteNo = <?php echo $startingQuoteNo; ?>;
		var maxQuotes = <?php echo $numquotes ?>;
		var pageName = "<?php echo $_SERVER['PHP_SELF']?>";
	</script>
</head>
<body>
	<div class="page">
		<div id="pillar-left" class="pillar">
			<div class="pillar-container">
				<div class="arrow-container">&lt;</div>
			</div>
		</div>

		<div class="quote-container ui-helper-clearfix">
			<?php 
				$firstquote = getQuote($startingQuoteNo);
				$firstquote = json_decode($firstquote, true);
				$firstquote = $firstquote;
			?>

			<p class="quote" id="quote">
				<?php echo $firstquote['quote']; ?>
			</p>
			<p class="quoteby" id="quoteby">
				<?php echo $firstquote['by']; ?>
			</p>
		</div>

		<div id="pillar-right" class="pillar">
			<div class="pillar-container">
				<div class="arrow-container">&gt;</div>
			</div>
		</div>
	</div>

	<div class="footer">
		<div id="links">
			<div class="footer-container swf hideme">
				<a id="copy-to-clipboard-quote" class="quote" href="#">COPY</a>
			</div>
			<div class="footer-container swf hideme">
				<a id="copy-to-clipboard-url" class="quote" href="#">URL</a>
			</div>
			<div class="footer-container hideme">
				<a id="submit-quote-link" class="quote" href="#">ADD</a>
			</div>
			<div class="footer-container">
				<a id="randomquote" class="quote" href="#">RAND</a>
			</div>

		</div>
		<div id="form">
			<form id="quote-form">
				<input class="quote-text" type="text" name="quote" width="100%"/>
			</form>
		</div>
	</div>
<body>
</html>