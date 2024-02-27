<?php
if(!defined('_TUBEWEB_')) exit;

?>

<div id="wrapper">
        <div id="header">
            <div id="hd">
                <div id="hd_inner">
                    <div class="hd_bnr">
                        <span><?php echo display_banner(2, $pt_id); ?></span>
                    </div>
                    <h1 class="hd_logo">
                        <?php echo display_logo(); ?>
                    </h1>
                </div>
            </div>
        </div>
    <div id="container">
<?php
if(!defined('_INDEX_')) { // index가 아니면 실행
    $gp_string = $_SERVER['REQUEST_URI'];
    $gp_find = "?";
    $pos = strpos($gp_string, $gp_find);

    $gp_string_val = substr($gp_string, 0, $pos);

    if('/shop/list.php' != $gp_string_val && '/shop/listtype.php?'  != $gp_string_val && '/shop/cart.php' != $_SERVER['REQUEST_URI'] && '/shop/orderform.php' != $_SERVER['REQUEST_URI']) {
        echo '<div class="sub_cont"><div class="cont_inner">'.PHP_EOL;
    } else {
        echo '<div class="list_sub"><div class="cont_inner">'.PHP_EOL;
    }
}
?>
