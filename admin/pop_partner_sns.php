<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once('./_head.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "소셜 네트워크 설정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$pt = get_partner($mb_id);

?>

<form name="fmemberform" method="post" action="./pop_partner_sns_update.php">
    <input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">

    <div id="memberform_pop" class="new_win">
        <h1><?php echo $tb['title']; ?></h1>
        <section class="new_win_desc marb50">
            <?php echo pt_pg_anchor($mb_id,$mb['use_pg']); ?>
            <h3 class="anc_tit">소셜네트워크서비스(SNS : Social Network Service)</h3>
            <p>※ 해당 설정은 본사 DB를 사용하고 있는 경우에만 사용 가능합니다. </p>

            <div class="tbl_frm01">
                <table>
                    <colgroup>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">소셜네트워크 로그인</th>
                        <td><label><input type="checkbox" name="de_sns_login_use" value="1"<?php echo $pt['de_sns_login_use']?' checked':''; ?>> 사용함</label></td>
                    </tr>
                    <tr>
                        <th scope="row">네이버 Client ID</th>
                        <td>
                            <input type="text" name="de_naver_appid" value="<?php echo $pt['de_naver_appid']; ?>" class="frm_input" size="50">
                            <a href="https://developers.naver.com/products/login/api/" target="_blank" class="btn_small grey">앱 등록하기</a>
                            <?php echo help('앱설정시 Callback URL에 http://도메인주소/plugin/login-oauth/login_with_naver.php 입력'); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">네이버 Client Secret</th>
                        <td><input type="text" name="de_naver_secret" value="<?php echo $pt['de_naver_secret']; ?>" class="frm_input" size="50"></td>
                    </tr>
                    <tr>
                        <th scope="row">카카오 REST API Key</th>
                        <td>
                            <input type="text" name="de_kakao_rest_apikey" value="<?php echo $pt['de_kakao_rest_apikey']; ?>" class="frm_input" size="50">
                            <a href="https://developers.kakao.com/apps/new" target="_blank" class="btn_small grey">앱 등록하기</a>
                            <?php echo help('카카오 사이트 설정에서 플랫폼 > Redirect Path에 /plugin/login-oauth/login_with_kakao.php 라고 입력'); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart30">goo.gl 짧은주소 만들기</h3>
            <div class="tbl_frm01">
                <table>
                    <colgroup>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">구글 짧은주소 API Key</th>
                        <td>
                            <input type="text" name="de_googl_shorturl_apikey" value="<?php echo $pt['de_googl_shorturl_apikey']; ?>" class="frm_input" size="50">
                            <a href="http://code.google.com/apis/console/" target="_blank" class="btn_small grey">API Key 발급받기</a>
                            <?php echo help('트위터, 페이스북과 같은 SNS가 유행하면서 제한된 문자수를 극복하기 위해 또는 너무 길어<br>지저분해 보이는 URL을 짧고 간결하게 표시하기 위한 짧은 URL(Short URL Service) 서비스입니다.<br><span class="fc_084">위 "API Key 발급받기" 버튼을 클릭 후 접속하셔서 API Key를 발급받습니다.<br>입력하지 않으면 실제 URL을 보내게 됩니다.</span><br>짧은주소 변환 후 <span class="fc_red">예시) http://goo.gl/bmjqtY</span>'); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart30">인스타그램 연동</h3>
            <div class="local_cmd01">
                <p>※ 인스타그램 설정 완료시 쇼핑몰 하단부분에 노출됩니다.</p>
            </div>
            <div class="tbl_frm01">
                <table>
                    <colgroup>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">INSTAGRAM URL</th>
                        <td>
                            <span>https://www.instagram.com/</span>
                            <input type="text" name="de_insta_url" value="<?php echo $pt['de_insta_url']; ?>" class="frm_input">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">CLIENT ID</th>
                        <td>
                            <input type="text" name="de_insta_client_id" value="<?php echo $pt['de_insta_client_id']; ?>" class="frm_input" size="50">
                            <a href="https://www.instagram.com/developer/register" target="_blank" class="btn_small grey">INSTAGRAM 시작</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Valid redirect URIs</th>
                        <td><input type="text" name="de_insta_redirect_uri" value="<?php echo $pt['de_insta_redirect_uri']; ?>" class="frm_input" size="50"></td>
                    </tr>
                    <tr>
                        <th scope="row">ACCESS_TOKEN</th>
                        <td>
                            <input type="text" name="de_insta_access_token" value="<?php echo $pt['de_insta_access_token']; ?>" class="frm_input" size="50">
                            <a href="javascript:createAccessToken();" class="btn_small grey">ACCESS_TOKEN 생성하기</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="anc_tit mart30">SNS URL 설정</h3>
            <div class="local_cmd01">
                <p>※ SNS URL 설정 완료시 쇼핑몰 하단부분에 아이콘이 노출됩니다.</p>
            </div>
            <div class="tbl_frm01">
                <table class="tablef">
                    <colgroup>
                        <col class="w180">
                        <col>
                        <col class="w180">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">FACEBOOK</th>
                        <td><input type="text" name="de_sns_facebook" value="<?php echo $pt['de_sns_facebook']; ?>" class="frm_input wfull"></td>
                        <th scope="row">TWITTER</th>
                        <td><input type="text" name="de_sns_twitter" value="<?php echo $pt['de_sns_twitter']; ?>" class="frm_input wfull"></td>
                    </tr>
                    <tr>
                        <th scope="row">INSTAGRAM</th>
                        <td><input type="text" name="de_sns_instagram" value="<?php echo $pt['de_sns_instagram']; ?>" class="frm_input wfull"></td>
                        <th scope="row">PINTEREST</th>
                        <td><input type="text" name="de_sns_pinterest" value="<?php echo $pt['de_sns_pinterest']; ?>" class="frm_input wfull"></td>
                    </tr>
                    <tr>
                        <th scope="row">NAVER BLOG</th>
                        <td><input type="text" name="de_sns_naverblog" value="<?php echo $pt['de_sns_naverblog']; ?>" class="frm_input wfull"></td>
                        <th scope="row">NAVER BAND</th>
                        <td><input type="text" name="de_sns_naverband" value="<?php echo $pt['de_sns_naverband']; ?>" class="frm_input wfull"></td>
                    </tr>
                    <tr>
                        <th scope="row">KAKAOTALK</th>
                        <td><input type="text" name="de_sns_kakaotalk" value="<?php echo $pt['de_sns_kakaotalk']; ?>" class="frm_input wfull"></td>
                        <th scope="row">KAKAOSTORY</th>
                        <td><input type="text" name="de_sns_kakaostory" value="<?php echo $pt['de_sns_kakaostory']; ?>" class="frm_input wfull"></td>
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
