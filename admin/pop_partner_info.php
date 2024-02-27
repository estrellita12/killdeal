<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once('./_head.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "가맹점 정보 수정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$pt = get_partner($mb_id);

?>

<form name="fmemberform" method="post" action="./pop_partner_info_update.php">
    <input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">

    <div id="memberform_pop" class="new_win">
        <h1><?php echo $tb['title']; ?></h1>

        <section class="new_win_desc marb50">

            <?php echo pt_pg_anchor($mb_id,$mb['use_pg']); ?>

            <h3 class="anc_tit">기본정보</h3>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w130">
                        <col>
                        <col class="w130">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">쇼핑몰 분양주소</th>
                        <td colspan="3"><a href="<?php echo $mb['homepage']; ?>" target="_blank" class="sitecode"><?php echo $mb['homepage']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">회원명 (아이디)</th>
                        <td><?php echo $mb['name']; ?> (<?php echo $mb['id']; ?>)</td>
                        <th scope="row">가맹점 신청일</th>
                        <td><?php echo $pt['reg_time']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">회원레벨</th>
                        <td><?php echo get_grade($mb['grade']); ?></td>
                        <th scope="row">승인여부</th>
                        <td><?php echo ($pt['state'])?"승인완료":"승인대기"; ?></td>
                    </tr>
                    <!--
                    <tr>
                        <th scope="row"><label for="theme">PC 쇼핑몰스킨</label></th>
                        <td>
                            <?php echo get_theme_select('theme', $mb['theme']); ?>
                        </td>
                        <th scope="row"><label for="mobile_theme">모바일 쇼핑몰스킨</label></th>
                        <td>
                            <?php echo get_mobile_theme_select('mobile_theme', $mb['mobile_theme']); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">추가 판매수수료</th>
                        <td>
                            <input type="text" name="payment" value="<?php echo $mb['payment']; ?>" class="frm_input" size="10">
                            <select name="payflag">
                                <?php echo option_selected('0', $mb['payflag'], '%'); ?>
                                <?php echo option_selected('1', $mb['payflag'], '원'); ?>
                            </select>
                            <?php echo help('판매수수료를 개별적으로 추가적립 하실 수 있습니다.'); ?>
                        </td>
                        <th scope="row">개별 도메인</th>
                        <td>
                            <span class="sitecode">www.</span><input type="text" name="homepage" value="<?php echo $mb['homepage']; ?>" class="frm_input" placeholder="Ex) sample.com">
                            <?php echo help('단독서버인경우만 입력하세요. (포워딩으로 설정된 도메인은 입력금지)'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">입금계좌</th>
                        <td colspan="3">
                            <input type="text" name="bank_name" value="<?php echo $mb['bank_name']; ?>" class="frm_input" placeholder="은행명">
                            <input type="text" name="bank_account" value="<?php echo $mb['bank_account']; ?>" class="frm_input" placeholder="계좌번호" size="30">
                            <input type="text" name="bank_holder" value="<?php echo $mb['bank_holder']; ?>" class="frm_input" placeholder="예금주명">
                            <?php echo help('위 계좌정보는 수수료 정산시 이용 됩니다. 정확히 입력해주세요.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">본사지정 권한</th>
                        <td colspan="3">
                            <input type="checkbox" name="use_pg" value="1" id="use_pg"<?php echo get_checked($mb['use_pg'], '1'); ?>> <label for="use_pg">개별 PG결제 허용</label>
                            <input type="checkbox" name="use_good" value="1" id="use_good"<?php echo get_checked($mb['use_good'], '1'); ?>> <label for="use_good">개별 상품판매 허용</label>
                        </td>
                    </tr>
                    -->
                </tbody>
                </table>
            </div>
            <!--
            <h3 class="anc_tit mart30">포인트정보</h3>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w130">
                        <col>
                        <col class="w130">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">포인트</th>
                        <td><label><input type="checkbox" name="usepoint_yes" value="1"<?php echo $pt['usepoint_yes']?' checked':''; ?>> 사용함</label></td>
                        <th scope="row">최소 결제포인트</th>
                        <td>
                            <input type="text" name="usepoint" value="<?php echo number_format($pt['usepoint']); ?>" class="frm_input w80" onkeyup="addComma(this)"> 원
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">온라인쿠폰</th>
                        <td><label><input type="checkbox" name="coupon_yes" value="1"<?php echo $pt['coupon_yes']?' checked':''; ?>> 사용함</label></td>
                        <th scope="row">인쇄용쿠폰</th>
                        <td><label><input type="checkbox" name="gift_yes" value="1"<?php echo $pt['gift_yes']?' checked':''; ?>> 사용함</label></td>
                    </tr>
                    <tr>
                        <th scope="row">비회원 구매 허용</th>
                        <td colspan="3"><label><input type="checkbox" name="non_mem_allow" value="1"<?php echo $pt['non_mem_allow']?' checked':''; ?>> 허용함</label></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            -->
            <h3 class="anc_tit mart30">사업자정보</h3>
            <p>※ 아래 사업자정보는 쇼핑몰 하단에 노출되며 노출안함으로 설정시 본사 사업자정보가 노출 됩니다.</p>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">쇼핑몰 사업자노출 여부</th>
                        <td>
                            <?php echo radio_checked('saupja_yes', $pt['saupja_yes'], '1', '노출함'); ?>
                            <?php echo radio_checked('saupja_yes', $pt['saupja_yes'], '0', '노출안함'); ?>			
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="shop_name">쇼핑몰명</label></th>
                        <td>
                            <input type="text" name="shop_name" value="<?php echo $pt['shop_name']; ?>" id="shop_name" class="frm_input" size="30">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="shop_name_us">쇼핑몰 영문명</label></th>
                        <td>
                            <input type="text" name="shop_name_us" value="<?php echo $pt['shop_name_us']; ?>" id="shop_name_us" class="frm_input" size="30">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">사업자유형</th>
                        <td>
                            <?php echo radio_checked('company_type', $pt['company_type'], '0', '일반과세자'); ?>
                            <?php echo radio_checked('company_type', $pt['company_type'], '1', '간이과세자'); ?>
                            <?php echo radio_checked('company_type', $pt['company_type'], '2', '면세사업자'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_name">회사명</label></th>
                        <td>
                            <input type="text" name="company_name" value="<?php echo $pt['company_name']; ?>" id="company_name" class="frm_input" size="30">
                            <em>세무서에 등록되어 있는 회사명 입력</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_owner">대표자명</label></th>
                        <td>
                            <input type="text" name="company_owner" value="<?php echo $pt['company_owner']; ?>" id="company_owner" class="frm_input" size="30">
                            <em>예) 홍길동</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_saupja_no">사업자등록번호</label></th>
                        <td>
                            <input type="text" name="company_saupja_no" value="<?php echo $pt['company_saupja_no']; ?>" id="company_saupja_no" class="frm_input" size="30">
                            <em>예) 000-00-00000</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_item">업태</label></th>
                        <td>
                            <input type="text" name="company_item" value="<?php echo $pt['company_item']; ?>" id="company_item" class="frm_input" size="30">
                            <em>예) 소매업</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_service">종목</label></th>
                        <td>
                            <input type="text" name="company_service" value="<?php echo $pt['company_service']; ?>" id="company_service" class="frm_input" size="30">
                            <em>예) 전자상거래업</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_zip">사업장우편번호</label></th>
                        <td>
                            <input type="text" name="company_zip" maxlength="5" value="<?php echo $pt['company_zip']; ?>" id="company_zip" class="frm_input" size="8">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_addr">사업장주소</label></th>
                        <td>
                            <input type="text" name="company_addr" value="<?php echo $pt['company_addr']; ?>" id="company_addr" class="frm_input" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="tongsin_no">통신판매업신고번호</label></th>
                        <td>
                            <input type="text" name="tongsin_no" value="<?php echo $pt['tongsin_no']; ?>" id="tongsin_no" class="frm_input" size="30">
                            <em>예) <?php echo TB_TIME_YEAR.'-서울강남-0000호'; ?></em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_tel">대표전화번호</label></th>
                        <td>
                            <input type="text" name="company_tel" value="<?php echo $pt['company_tel']; ?>" id="company_tel" class="frm_input" size="30">
                            <em>예) 1544-0000, 070-0000-0000</em>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="company_fax">팩스번호</label></th>
                        <td>
                            <input type="text" name="company_fax" value="<?php echo $pt['company_fax']; ?>" id="company_fax" class="frm_input" size="30">
                            <em>예) 02-0000-0000</em>
                        </td>
                    </tr>	
                    <tr>
                        <th scope="row"><label for="info_name">정보책임자 이름</label></th>
                        <td>
                            <input type="text" name="info_name" value="<?php echo $pt['info_name']; ?>" id="info_name" class="frm_input" size="30">
                            <em>예) 홍길동</em>
                        </td>
                    </tr>		
                    <tr>
                        <th scope="row"><label for="info_email">정보책임자 e-mail</label></th>
                        <td>
                            <input type="text" name="info_email" value="<?php echo $pt['info_email']; ?>" id="info_email" class="email frm_input" size="30">
                            <em>예) help@domain.com</em>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart30">약관설정</h3>
	        <p>※ 본사 DB를 사용하는 경우에만 반영되며, 아래 설정값이 없으면 본사 설정값으로 대체되어 노출됩니다.</p>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
	                <tr>
		                <th scope="row">회원가입약관<br>(회원가입 시)</th>
	                    <td><textarea name="shop_provision" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $pt['shop_provision']); ?></textarea></td>
	                </tr>
	                <tr>
		                <th scope="row">개인정보 수집 및 이용<br>(회원가입 시)</th>
	    	            <td><textarea name="shop_private" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $pt['shop_private']); ?></textarea></td>
	                </tr>
	                <tr>
		                <th scope="row">개인정보처리방침</th>
		                <td><textarea name="shop_policy" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $pt['shop_policy']); ?></textarea></td>
	                </tr>
	            </tbody>
	            </table>
            </div>

            <div class="btn_confirm">
                <input type="submit" value="저장" class="btn_medium" accesskey="s">
                <button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
            </div>
 
        </section>
    </div>
</form>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");

?>
