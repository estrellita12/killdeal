function sendPostMessage(re_url) {
    //콕팜(콕쇼핑) 도메인
    //개발 : https://cokfarmdev.nonghyup.com:8990
    //운영 : https://cokfarm.nonghyup.com:8990
    var url = "https://cokfarmdev.nonghyup.com:8990"; //개발
    
    var param = {
        "gubun" : "parksajang",
        "redirectUrl" : re_url
    };
    
    window.parent.postMessage(param, url);
};

//fetch 함수
function call_fetch(url, bdy, meth="POST"){
	return fetch(`${tb_url}/fetch/${url}.php`, {
		method: meth,
		headers: {
			'Content-Type': 'application/json; charset=utf-8',
		},
		body: JSON.stringify(bdy),
	});
}