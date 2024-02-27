<?php 
include_once('../common.php');

$mkey = '84be075e73650f2fe441bdb24527831383ca351e3245c5458768f31e672a6a6ebfa68a16e3cba58d034fff60fdc2ad50e4e9159dd9d787aad4e473a2b316a30e32e335a1eccca3937405f2844edc0ab21b4aa7b79c9260cfd4db3a1ff9ac134d064141bbc866bf3ee870cc95a6d3acff4f203ae6eb3b4593f17e784c69e71050e657518474384e31f171387948ecc2e9';
?>
<html>
<body>
<form action="test_3.php" method="get">
	<input type="text" name="mkey" value="<?php echo $mkey ?>">
	<input type="submit">
</form>
</body>
</html>