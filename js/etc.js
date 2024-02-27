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