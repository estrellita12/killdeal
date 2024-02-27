<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="company">
    <p class="ft_logo">
        <?php echo display_footer_logo();  ?>
    </p>
    <ul>
        <li>
            <?php echo $config['company_name']; ?> 
            <span class="g_hl"></span> 대표자 : <?php echo $config['company_owner']; ?>
            <span class="g_hl"></span> 주소 : <?php echo $config['company_addr']; ?>
            <span class="g_hl"></span> 쇼핑 문의 : <?php echo $config['company_tel']; ?>
            <span class="g_hl"></span> 이메일 : <?php echo $super['email']; ?>
            <br>
            <span class="g_h1"></span> 제휴사 : (주)마케팅큐브&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;대표: 진병두
            <br>통신판매업신고 : <?php echo $config['tongsin_no']; ?>
            <span class="g_hl"></span>사업자등록번호 : <?php echo $config['company_saupja_no']; ?> <a  href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall grey2 marl5">사업자정보확인</a>  
            <p class="cptxt">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
        </li>
    </ul>
</div>
