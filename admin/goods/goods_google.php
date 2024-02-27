<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div id="ajax-loading" style="display:block; background:url('/img/ajax-loader.gif') no-repeat 50% 50% rgba(230,230,230,0.5)">
    <div style="text-align:center; width:100%; position:absolute; top:55%; z-index:1000; font-size:14px;">
        <p>잠시만 기다려 주세요...</p>
        <p>최대 5초 정도 소요 될 수 있습니다.</p>
    </div>
</div>

<h2>기본검색</h2>
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
    <table class="tablef">
    <colgroup>
        <col class="w100">
        <col>
    </colgroup>
    <tbody>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl" id="sfl" class="w120">
                <option value="ezadmin">이지어드민</option>
                <option value="sbnet">사방넷</option>
                <option value="gsname">상품명</option>
                <option value="ctg">카테고리</option>
                <option value="sheet">시트</option>
			</select>
			<input type="text" name="stx" id="stx" value="" class="frm_input" size="30" onkeypress="if(event.keyCode==13){getFilter()}">
            <p class="frm_info fs11">기본적으로 포함 검색으로 진행되며 컴마(,)를 이용한 검색도 가능합니다. </p>
		</td>
	</tr>
	<tr>
		<th scope="row">시트</th>
		<td>
			<select name="sheet" id="sheet" class="w150">
                <option value="">전체</option>
                <option value="용인메이저">용인메이저</option>
                <option value="MWO공급사">MWO공급사</option>
			</select>
		</td>
	</tr>
    <tr>
		<th scope="row">상품가격</th>
		<td>
			<select name="q_price_field" id="q_price_field" class="w120">
                <option value="aprice">A가</option>
                <option value="bprice">B가</option>
                <option value="cprice">C가</option>
                <option value="dprice">D가</option>
                <option value="eprice">E가</option>
                <option value="cost">원가</option>
                <option value="consumer">소비자가</option>
			</select>
			<label for="fr_price" class="sound_only">상품가격 시작</label>
			<input type="text" name="fr_price" value="" id="fr_price" class="frm_input w100" size="6"> 원 이상 ~
			<label for="to_price" class="sound_only">상품가격 끝</label>
			<input type="text" name="to_price" value="" id="to_price" class="frm_input w100" size="6"> 원 이하
		</td>
	</tr>
    </tbody>
    </table>
</div>
<div class="btn_confirm">
    <input type="button" value="검색" class="btn_medium" onclick="getFilter()">
    <input type="button" value="초기화" id="frmRest" class="btn_medium grey" onclick="resetFilter()">
</div>
<br>
<link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
<style>
        .tabulator {
            border-color: #eee !important;
        }
        .tabulator .tabulator-header {
            font-weight: 500 !important;
            border-bottom-color: #eee !important;
        }
        .tabulator .tabulator-header .tabulator-col {
            background: #ffffff !important;
            border-right-color: #eee !important;
        }
        .tabulator-header-filter{
            margin-top:4px !important;
        }
        .tabulator-header-filter input[type="search"]{
            border:1px solid #ddd;
            padding:0 !important;
        }
        .tabulator-header-filter input[type="number"]{
            border:1px solid #ddd;
            padding:0 !important;
        }
        .tabulator .tabulator-row .tabulator-cell {
            border-right-color: #eee !important;
        }
        .tabulator .tabulator-footer {
            background-color: #fefefe;
            border-top-color: #eee !important;
            font-weight: 500 !important;
        }
        .tabulator-row.tabulator-row-even {
            background-color: #fafafa;
        }
        .tabulator-row.tabulator-selectable:hover {
            background-color: #ebebeb;
        }
        .tabulator .tabulator-footer .tabulator-page.active {
            color: darkred;
        }
        .tabulator .tabulator-footer .tabulator-page {
            border-color: #fff;
        }

</style>
<form>

<div class="local_ov mart30">
	전체 : <b class="fc_red" id="tot_cnt"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
    <button type="button" id="frmGoodsExcel" class="btn_lsmall white" onclick="download()"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</button>
</div>
<div class="tbl_head02">
    <table id="sheets_data" class="tablef"></table>
</div>
</form>
<script>
function onlyNumber(str){
    var regex = /[^0-9]/g;
    var result = str.replace(regex, "");
    return result;
}

function comma(num){
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

</script>


<script src="https://cdn.jsdelivr.net/gh/tanaikech/GetAccessTokenFromServiceAccount_js@master/getaccesstokengromserviceaccount_js.min.js"></script>
<script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
<script>

let origin = "";
let table = "";

const object = {
private_key: "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCEYD4WFJJZifz2\nuczyZ7v8B+HTrUP0nS51eG73ZuUgTF0X1dMWDZCR5ZU/Us2rTmb9erh/ttgg1F6f\n6UXJ4aLn2E652WILau+uaU8M+eJfsw+LCmiAVZSQ9+FHbeX6OO3yxzJT/+qu3xxK\nVo0NpH8rOGJTdENPysVecPX+spIBfvo3pNfBCf2g/J/fntSW1d6P21XW41pO8VMh\nMdJzCj9CwAR2ce6j+6ZB5kvrI3XwqrA6fr3V6OPt/7uE1YwA2jL6tF4lc0YfxZGS\nvRjRBr8qup6bdlV4n9+4rMeZIra+PLO4GBmJ7wCm75hcAuxu4B/wsDLT6ZFRwXVt\nBS4Nj6+BAgMBAAECggEAQAO8snfTSBqLKpMyUX0pspjrM66j4KyMNYF+hASNw/85\nu5eLIyx/H5a7BGraC7/33ReWFijJPqMEeWdY+OY1HdIETCqcF7JoYtsJP9itiKLy\nXsYzP/BiznIYzq6OGuGh7Bg5NdbZ2iQJrcdKIfFNEA0Nu5bLIFCJ/oA47ajUI4Ve\nlfDtQTWtuqm7KlDMZtP+i5MtFDIvUaf0JB4Gvoq45sqqylm76P+ee/dUWUdGNFtD\nzaEHBoRtKozCKn20biXOIlfJwaSsID8OPeXIxsbWcFu5cRqSdS5cgP8TbmysxOFt\nZkhcLf1k1YgIg8tIBdIryWMw4OdU4xKGuGcDutTC2wKBgQC5Em9hUXoy7sld8mtm\nfI0NNH8Pg+6I8jmSplqUVGxttmnim9Mm5sPgYOcznHos24uuw5YdMrhUMcmNf8gF\nhosI4PfW2pnzFn9KOEz5L0ozyhziITTem3qNTXEfRsq2SwfCD4vSBayzGlw3BnNG\nRTgq9PKD2yY9xZf/VhMMs/RkuwKBgQC3G8D+bv0cWqKVrdL9V5QJohL+R+E4rR29\n1oAP3+oKGHAtiLmevDldSP5I3npwgA+czlq27WMW7CYdnLDmXUa/1IUaY/RqHmJI\nr7vAv11CU2uICf4qRRHjxdS7qNNxQOjwez0N6pTn9ia6TnuU6oo/IaQuJzIMSZpi\nUfqt6wQW8wKBgHnoA8fl5IliMvAYO9iRWFQHbV6p99jrPTM1Mtsb1SRbkNm87NRm\nE0Zcbk7X1r5vi0399YacH0EOXoY/UmEZY8HgdkBnVBsEiao49bL6DHWav3XQi8PK\nRGqJRWdluSdkuuKAXQhlxoFfbrisHgh+leXt3UUveLwdyOZfK0Ml0mj7AoGAFm4T\n6hb2cm6309YDLn136OYtpXBwqlyqdAK+lTM8nBf6Rdmlw0gTTtYOMCbwoK9POkoc\n2qOhq8Epuh7jnJR4gi8qTt1Hp2gpafX87dODPQiy92sh81OaqWgmcwZvQERPRIYU\nKIw/yVphzBipEsjYPnuEfRLYEqFBhCG+r2dGjPkCgYAiVRVvIBqrX3yLG1xLV36L\nXWARF99EGv06W7cPNySVwRkiHYg8uOV3x71iW3wrfr56IPDBQqrfrc8+YfLty+LF\nh75FNlX9UX/RCPppNzIQSFObXxPu46p+Yc2RMJ7Fm7Q+TZRpWz+PWm8hsCPcFnq6\n5VX2DDByMhfAfk9rWsrSqw==\n-----END PRIVATE KEY-----\n",
    client_email: "newbiz@newbz-378501.iam.gserviceaccount.com",
    scopes: ["https://www.googleapis.com/auth/spreadsheets.readonly"],
};

const DISCOVERY_DOC = 'https://sheets.googleapis.com/$discovery/rest?version=v4';

function gapiLoaded() {
    gapi.load('client', initializeGapiClient);
}

async function initializeGapiClient() {
    gapi.auth.setToken(await GetAccessTokenFromServiceAccount.do(object));
    await gapi.client.init({
    //apiKey: API_KEY,
    discoveryDocs: [DISCOVERY_DOC],
    });
    gapiInited = true;
    getData();
}

async function getData() {
    let response;
    let range;
    let dataList = [];
    let major = [];
    let mwo = [];
    var tmp;

    try {
        response = await gapi.client.sheets.spreadsheets.values.get({
        spreadsheetId: '1gc-fvfQxq_P8LfbUKsI865JIO55XgUUCjeyKmK6S1YM',
            range: '용인메이저!A9:AG'
    });

        range = response.result;
        if (!range || !range.values || range.values.length == 0) {
            console.log("No values found.");
            return;
        }else{
            tmp = range.values.map((cell)=>{
            var data = {"ezadmin":"","sbnet":'',"sheet":"용인메이저","ctg":'',"gsname":'',"aprice":'',"bprice":'',"cprice":'',"dprice":"","eprice":"","cost":"","consumer":""};
            if(cell[4]){
                data['ezadmin'] = cell[4]?cell[4]:'';
                data['sbnet'] = cell[6]?cell[6]:'';
                data['ctg'] = cell[5]?cell[5]:'';
                data['gsname'] = cell[11]?cell[11]:'';
                data['aprice'] = cell[20]?onlyNumber(cell[20]):"";
                data['bprice'] = cell[25]?onlyNumber(cell[25]):"";
                data['cprice'] = cell[26]?onlyNumber(cell[26]):"";
                data['dprice'] = cell[27]?onlyNumber(cell[27]):"";
                data['eprice'] = cell[28]?onlyNumber(cell[28]):"";
                data['cost'] = cell[18]?onlyNumber(cell[18]):"";
                data['consumer'] = cell[16]?onlyNumber(cell[16]):"";
            }
            return data;
            });
            tmp = tmp.filter((c)=>c['ezadmin'].length>3);
        }
    } catch (err) {
        console.log(err.message);
        return;
    }
    origin = tmp;

    try {
        response = await gapi.client.sheets.spreadsheets.values.get({
        spreadsheetId: '1gc-fvfQxq_P8LfbUKsI865JIO55XgUUCjeyKmK6S1YM',
            range: 'MWO공급사!A4:AK'
    });

        range = response.result;
        if (!range || !range.values || range.values.length == 0) {
            console.log("No values found.");
            return;
        }else{
            tmp = range.values.map((cell)=>{
            var data = {"ezadmin":"","sbnet":'',"sheet":"MWO공급사","ctg":'',"gsname":'',"aprice":'',"bprice":'',"cprice":'',"dprice":"","eprice":"","cost":"","consumer":""};
            if(cell[13]){
                data['ezadmin'] = cell[6]?cell[6]:'';;
                data['sbnet'] = cell[8]?cell[8]:'';
                data['ctg'] = cell[33]?cell[33]:'';
                data['gsname'] = cell[13]?cell[13]:'';
                //data['aprice'] = cell[26]?onlyNumber(cell[26]):"";
                data['aprice'] = "";
                data['bprice'] = cell[27]?onlyNumber(cell[27]):"";
                data['cprice'] = cell[28]?onlyNumber(cell[28]):"";
                data['dprice'] = cell[29]?onlyNumber(cell[29]):"";
                data['eprice'] = "";
                data['cost'] = cell[20]?onlyNumber(cell[20]):"";
                data['consumer'] = cell[18]?onlyNumber(cell[18]):"";
            }
            return data;
        });
            tmp = tmp.filter((c)=>c['ezadmin'].length>0);
        }
    } catch (err) {
        console.log(err.message);
        return;
    }
    origin = origin.concat(tmp);

    // ----------------------------------------------------------------
    try {
        response = await gapi.client.sheets.spreadsheets.values.get({
        spreadsheetId: '1gc-fvfQxq_P8LfbUKsI865JIO55XgUUCjeyKmK6S1YM',
            range: '25P!A2:AD'
    });

        range = response.result;
        if (!range || !range.values || range.values.length == 0) {
            console.log("No values found.");
            return;
        }else{
            tmp = range.values.map((cell)=>{
            var data = {"ezadmin":"","sbnet":'',"sheet":"25P","ctg":'',"gsname":'',"aprice":'',"bprice":'',"cprice":'',"dprice":"","eprice":"","cost":"","consumer":""};
            if(cell[4]){
                data['ezadmin'] = cell[4]?cell[4]:'';;
                data['sbnet'] = cell[6]?cell[7]:'';
                data['ctg'] = cell[5]?cell[5]:'';
                data['gsname'] = cell[12]?cell[12]:'';
                data['aprice'] = cell[21]?onlyNumber(cell[21]):"";
                data['bprice'] = cell[26]?onlyNumber(cell[26]):"";
                data['cprice'] = cell[27]?onlyNumber(cell[27]):"";
                data['dprice'] = cell[28]?onlyNumber(cell[28]):"";
                data['eprice'] = cell[29]?onlyNumber(cell[29]):"";
                data['cost'] = cell[19]?onlyNumber(cell[19]):"";
                data['consumer'] = cell[17]?onlyNumber(cell[17]):"";
            }
            return data;
        });
            tmp = tmp.filter((c)=>c['ezadmin'].length>0);
        }
    } catch (err) {
        console.log(err.message);
        return;
    }
    origin = origin.concat(tmp);


    table = new Tabulator("#sheets_data", {
        layout: "fitColumns",
        movableColumns: true,
        pagination: true,
        paginationSize: 30,
        paginationSizeSelector: [10, 30, 50, 100, true],
        data: origin,
        //initialSort: initialSort,
        columns:[
            {title:"시트Sheet", field:"sheet",width:80 },
            {title:"이지어드민", field:"ezadmin", sorter:"number",width:80},
            {title:"사방넷", field:"sbnet", sorter:"number",width:80},
            {title:"카테고리", field:"ctg",width:80 },
            {title:"상품명", field:"gsname"},
            {title:"소비자가", field:"consumer",sorter:"number",hozAlign:"center", width:80, formatter:function(cell){return comma(cell.getValue())}},
            {title:"원가", field:"cost",sorter:"number",hozAlign:"center", width:80, formatter:function(cell){return comma(cell.getValue())}},
            {title:"A가", field:"aprice", sorter:"number",hozAlign:"center", width:80, formatter:function(cell){return comma(cell.getValue())}},
            {title:"B가", field:"bprice", sorter:"number", hozAlign:"center", width:80 , formatter:function(cell){return comma(cell.getValue())}},
            {title:"C가", field:"cprice", sorter:"number", hozAlign:"center", width:80 , formatter:function(cell){return comma(cell.getValue())}},
            {title:"D가", field:"dprice", sorter:"number", hozAlign:"center", width:80 , formatter:function(cell){return comma(cell.getValue())}},
            {title:"E가", field:"eprice", sorter:"number", hozAlign:"center", width:80 , formatter:function(cell){return comma(cell.getValue())}}
        ],
    });

    table.on("dataLoaded", function(data){
        var el = document.getElementById("tot_cnt");
        el.innerHTML = comma(data.length);
    });

    table.on("dataFiltered", function(filters,rows){
        var el = document.getElementById("tot_cnt");
        el.innerHTML = comma(rows.length);
    });



    $("#ajax-loading").css("display","none");
}

function getFilter(){
    var sfl_select = document.getElementById('sfl');
    var sfl = sfl_select.options[sfl_select.selectedIndex].value;
    var stx = document.getElementById('stx').value;
    var filt = [];
    if( sfl && stx ){
        if( stx.includes(",") ){
            stx_li = stx.split(',');
            stx_li = stx_li.map((a)=>trim(a));
            stx = stx_li.join(" ");
            filt.push( {field:sfl, type:"keywords", value:stx, params:{matchAll:false} } );
        }else{
            if( sfl == "gsname" ){
                stx_li = stx.split(' ');
                stx_li = stx_li.map((a)=>trim(a));
                var sin = [];
                var not_in = "";
                for(const el of stx_li){
                    if(el.substr(0,1)=="-"){
                        if( not_in ){
                            not_in += "|";
                        }
                        not_in += el.substr(1);
                    }else{
                        sin.push(el);
                    }
                }
                stx = sin.join(" ");
                filt.push( {field:sfl, type:"keywords", value:stx, params:{matchAll:true} } );
                if( not_in ){
                    not_in = "^((?!"+not_in+").)*$";
                    not_in = new RegExp(not_in);
                    filt.push( {field:sfl, type:"regex", value:not_in} );
                }
                //stx = stx_li.join(" ");
                //filt.push( {field:sfl, type:"keywords", value:stx, params:{matchAll:true} } );
            }else{
                filt.push( {field:sfl, type:"like", value:stx } );
            }
        }
    }
    // ----------------------------------------------------------------
    var sheet_select = document.getElementById('sheet');
    var sheet = sheet_select.options[sheet_select.selectedIndex].value;
    if( sheet ){
        filt.push( {field:"sheet", type:"=", value: sheet } );
    }
    // ----------------------------------------------------------------
    var price_select = document.getElementById('q_price_field');
    var q_price = price_select.options[price_select.selectedIndex].value;
    var fr_price = document.getElementById('fr_price').value;
    var to_price = document.getElementById('to_price').value;
    if( q_price && fr_price ){
        fr_price = fr_price.replace(",","");
        filt.push( {field:q_price, type:">=", value:parseInt(fr_price) } );
    }
    if( q_price && to_price ){
        to_price = to_price.replace(",","");
        filt.push( {field:q_price, type:"<=", value:parseInt(to_price) } );
    }
    table.setFilter(filt);
    return false;
}

function resetFilter(){
    table.clearFilter();
    document.getElementById('stx').value="";
    document.getElementById('sheet').value="";
    document.getElementById('fr_price').value="";
    document.getElementById('to_price').value="";
}

function download(){
    table.download("xlsx", "data.xlsx", {sheetName:"상품목록"});
}
</script>
