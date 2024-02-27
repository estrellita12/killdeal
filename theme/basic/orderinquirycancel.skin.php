<?php if($pt_id == "golf") { 
		
	?>
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
													

					  },      
					  error: function(xhr, status, error) {
			                 alert(error);
		               }	
	        });
			
           
			
		});
		</script>
		<?php } ?>