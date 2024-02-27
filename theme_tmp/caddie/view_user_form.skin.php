<?php
if(!defined('_TUBEWEB_')) exit;
?>

<style>
     #star_grade a{
        text-decoration: none;
        font-size : 30px;
        color: lightgray;
    }
    #star_grade a.on{
        color: rgb(255,201,14);
    }
</style>

<div id="sit_qa_write" class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

    <form name="fitemqa" id="fitemqa" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fitemqa_submit(this);" enctype="MULTIPART/FORM-DATA" >
    <input type="hidden" name="mode" value="<?php echo $mode; ?>">
    <input type="hidden" name="it_mid" value="<?php echo $it_mid; ?>">
    <input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
    <input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
    <input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption>상품후기쓰기</caption>
        <colgroup>
            <col class="w100">
            <col class="w40">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">상품명</th>
            <td class="tar"><?php echo get_it_image($gs_id, $gs['simg1'], 50, 50); ?></td>
            <td><?php echo  get_text($gs['gname']); ?> </td>
        </tr>
        </tbody>
        </table>
        <table>
        <colgroup>
            <col class="w100">
            <col class="w200">
            <col class="w40">
            <col>
        </colgroup>
        <tbody>

        <tr>
            <th scope="row">*성별</th>
            <td><label class="marr10 "><input type="radio" name="gender" value="M" class="required" required><span class="padl5">남</span></label> <label><input type="radio" name="gender" value="W" required> <span class="padl5" class="required">여</span></label></td>
            <th scope="row">*연령</th>
            <td><label>
                <select name="age" class="required" required>
                    <option value="">연령 선택
                    <option value="1">19세 이하
                    <option value="2">20대
                    <option value="3">30대
                    <option value="4">40대
                    <option value="5">50대
                    <option value="6">60대
                    <option value="7">70대
                    <option value="8">80대 이상
                </select>
            </label></td>
        </tr>
        <tr>
            <th scope="row">레벨</th>
            <td colspan="3"><label>
                <select name="level" >
                    <option value="">레벨 선택
                    <option value="1">입문자
                    <option value="2">초급자
                    <option value="3">중급자
                    <option value="4">상급자
                    <option value="5">프로
                </select>
            </label></td>
        </tr>
        <tr>
            <th scope="row">*평점</th>
            <td class="tac" colspan="3">
                <p id="star_grade">
                    <a href="#" data-number="1">★</a>
                    <a href="#" data-number="2">★</a>
                    <a href="#" data-number="3">★</a>
                    <a href="#" data-number="4">★</a>
                    <a href="#" data-number="5">★</a>
                </p>
                <input type="hidden" name="score" id="score" value=0 required >
            </td>
        </tr>
        <tr>
            <th scope="row">*상품평<br>(20자 이상)</th>
            <td colspan="3"><label><textarea name="memo" rows="10" required itemname="질문" class="frm_textbox wufll required" minlength="20" ><?php echo $memo; ?></textarea></label></td>
        </tr>
        <tr>
            <th scope="row">사진등록</th>
            <td colspan="3">
                <input type="file" name="re_file" id="re_file">
                <input type="checkbox" name="re_file_del" id="bn_file_del"> <label for="re_file_del">삭제</label>
            </td>
        </tr>
        </tbody>
        </table>
    <br>
    <div>
        <p class="fc_999">- 상품평과 무관한 내용이거나 상품 재판매, 광고, 동일 문자의 반복 및 기타 불법적인 내용은 통보 없이 삭제 될 수 있습니다.</p>
        <p class="fc_999">- 반품, 취소 등의 CS관련 글은 고객센터>1:1문의로 이동될 수 있습니다.</p>
    </div>
    </div>
    <div class="win_btn">
        <input type="submit" value="작성완료" class="btn_lsmall">
        <a href="javascript:window.close();" class="btn_lsmall bx-white">창닫기</a>
    </div>
    </form>
</div>

    <script>
        $('#star_grade a').click(function(){
            $(this).parent().children("a").removeClass("on");  /* 별점의 on 클래스 전부 제거 */
            $(this).addClass("on").prevAll("a").addClass("on"); /* 클릭한 별과, 그 앞 까지 별점에 on 클래스 추가 */
            var ss = $(this).data("number");
            $("#score").val(ss);

            return false;
        });


    function fitemqa_submit(f) {
        if( $("#score").val() == "0" ){
            alert("평점을 선택해주세요");
            return false;
        }
        if(confirm("등록 하시겠습니까?") == false)
            return false;

        return true;
    }

    </script>
