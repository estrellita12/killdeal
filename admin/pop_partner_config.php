<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once('./_head.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "연동 정보 설정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$pt = get_partner($mb_id);

?>

<form name="fmemberform" id="fmemberform" action="./pop_partner_config_update.php" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">

    <div id="memberform_pop" class="new_win">
        <h1><?php echo $tb['title']; ?></h1>

        <section class="new_win_desc marb50">

            <?php echo pt_pg_anchor($mb_id,$mb['use_pg']); ?>

            <h3 class="anc_tit">전송정보</h3>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w200">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">DB연동 유무</th>
                        <td colspan="3">
                            <label><input type="checkbox" name="db_link_yes" value="1"<?php echo $pt['db_link_yes']?' checked':''; ?>> 연동함</label>
                            <p><?php echo help('체크박스를 선택하지 않은 경우, 본사 DB를 사용합니다.') ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">비회원 접근 허용 여부</th>
                        <td colspan="3"><label><input type="checkbox" name="non_mem_access" value="1"<?php echo $pt['non_mem_access']?' checked':''; ?>> 사용함</label></td>
                    </tr>
                    <tr>
                        <th scope="row">비회원 구매 허용 여부</th>
                        <td colspan="3"><label><input type="checkbox" name="non_mem_allow" value="1"<?php echo $pt['non_mem_allow']?' checked':''; ?>> 사용함</label></td>
                    </tr>
                   </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart20">연동정보</h3>
            <p>※ DB연동 유무에 체크된 경우에만 필요한 정보입니다. </p>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w200">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">전송 방식</th>
                        <td colspan="3">
                            <select name="send_type">
                                <option value="NONE"  <?php echo $pt['send_type']=="NONE"?' selected':''; ?> >NONE</option>
                                <option value="POST"  <?php echo $pt['send_type']=="POST"?' selected':''; ?> >POST</option>
                                <option value="GET"  <?php echo $pt['send_type']=="GET"?' selected':''; ?> >GET</option>
                                <option value="COOKIE"  <?php echo $pt['send_type']=="COOKIE"?' selected':''; ?> >COOKIE</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">암호화 사용 유무</th>
                        <td colspan="3"><label><input type="checkbox" name="encryption_yes" value="1"<?php echo $pt['encryption_yes']?' checked':''; ?>> 사용함</label></td>
                    </tr>
                    <tr>
                        <th scope="row">HOME URL</th>
                        <td colspan="3">
                            <input type="text" name="home_url" value="<?php echo $pt['home_url']; ?>" class="frm_input" size=40 placeholder="https://killdeal.co.kr">
                            <?php echo help('연동몰 홈페이지 URL을 설정 할 수 있습니다.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">LOGIN URL</th>
                        <td colspan="3">
                            <input type="text" name="login_url" value="<?php echo $pt['login_url']; ?>" class="frm_input" size=40 placeholder="https://killdeal.co.kr">
                            <?php echo help('로그인 필요시 리다이렉트할 URL주소, returnURL 설정이 가능한 경우 하드 코딩으로 설정합니다.'); ?>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart20">디자인정보</h3>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w200">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">별도의 head 스킨 사용 여부</th>
                        <td >
                            <!-- <input type="checkbox" name="head_skin_yes" value="1"<?php echo $pt['encryption_yes']?' checked':''; ?>> 사용함 -->
                            <?php echo help('head 디자인을 다르게 하고 싶은 경우 pt_id_head.skin.php 파일을 생성하여 사용합니다.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">별도의 tail 스킨 사용 여부</th>
                        <td >
                            <!-- <input type="checkbox" name="tail_skin_yes" value="1"<?php echo $pt['encryption_yes']?' checked':''; ?>> 사용함 -->
                            <?php echo help('tail 디자인을 다르게 하고 싶은 경우 pt_id_tail.skin.php 파일을 생성하여 사용합니다.'); ?>
                        </td>
                    </tr>

                </tbody>
                </table>
            </div>
 


            <div class="btn_confirm">
                <!-- <input type="submit" value="저장" class="btn_medium" accesskey="s"> -->
                <button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
            </div>
        </section>
    </div>
</form>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>
