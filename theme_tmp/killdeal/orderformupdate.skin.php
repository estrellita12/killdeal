<script src="https://killdeal.co.kr:443/js/jquery-1.8.3.min.js"></script> 
<script src="https://killdeal.co.kr:443/js/jquery-ui-1.10.3.custom.js"></script>
<script src="https://killdeal.co.kr:443/js/jquery.ajax-cross-origin.min.js"></script>
<script src="https://killdeal.co.kr:443/js/common.js?ver=20190610103806"></script>
<script src="https://killdeal.co.kr:443/js/slick.js"></script>

		<script>
		$(document).ready(function(){
					
			$.ajax({
				      
		              url : 'https://giftdev.e-hyundai.com/hb2efront_new/pointOpenAPI.do',
					  //url : 'hprox.php', //crossDomain문제로 Prox서버를 담당하는 페이지 호출
		              type : 'GET', 
				      data : {
			                     mem_id : '<?php echo $mem_no2;?>',
                                 shopevent_no : '<?php echo $shopevent_no2;?>',
								 proc_code : '<?php echo $proc_code2;?>',
								 chk_data : '<?php echo $mem_nm3;?>',
							     point : '<?php echo $u_point2;?>',
								 order_no : '<?php echo $order_no2;?>' ,
                                 media_cd : 'MW'
		                     },
				      dataType : 'XML',
		              success : function(result){  
							 alert("success");						

					  },      
					  error: function(xhr, status, error) {
			                 alert(error);
		               }	
	        });
			
           
			
		});
		</script>
	