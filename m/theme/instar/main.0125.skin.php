<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
//header( "Location: ../shop/list.php?ca_id=007" );
?>

<!-- 베스트상품 시작 {-->
<div class="m_main_cont">
	<!--<h2 class="mtit"><span>TEST</span></h2>-->
	<?php if($default['de_listing_best'] == '1') { ?>
		<!-- 베스트상품(수동) 카테고리별 베스트 시작 {-->
		<?php
		if($default['de_maintype_best']) {
			$list_best = unserialize(base64_decode($default['de_maintype_best']));
			$list_count = count($list_best);
			$tab_width = (float)(100 / $list_count);
			//2022-02-28 인플루언서몰 메인화면 태그 카테고리 (인기상품 제외 1,2,3 태그는 신상품 30으로 교체) 시작
			for($k=1; $k<$list_count; $k++) {
				$result = sql_query("select index_no from shop_goods where LEFT(ca_id, 3) = '00{$k}' and isopen = 1 ORDER BY index_no DESC LIMIT 30");
				$cate_string = '';
				while($row = sql_fetch_array($result)){
					$cate_string .= "{$row[index_no]},";
				} 				
				$list_best[$k]['code'] = substr($cate_string, 0, -1);
			}
			//2022-02-28 인플루언서몰 메인화면 태그 카테고리 (인기상품 제외 1,2,3 태그는 신상품 30으로 교체) 끝            
		?>
		<ul class="bestca_tab1">
			<?php for($i=0; $i<$list_count; $i++) { 
					$j = $i;
					if($i == '2'){
						$j = '3';
					} else if ($i == '3') {
						$j = '2';
					}
			?>
			<!-- 인기상품 , 골프클럽 , 골프용품, 골프패션 -->
			<li data-tab="bstab_c<?php echo $j; ?>"><span><?php echo "# ".trim($list_best[$i]['subj']); ?></span></li>
			<?php } ?>
		</ul>
		<div class="pr_desc wli2 bestca">
			<?php echo m_get_listtype_cate3($list_best, '160', '160', 20);//the last arg default 30 ?> 
			
		</div>
		<script>
		$(document).ready(function(){
			$(".bestca_tab1>li:eq(0)").addClass('active');
			$("#bstab_c0").show();

			$(".bestca_tab1>li").click(function() {
				var activeTab = $(this).attr('data-tab');
				$(".bestca_tab1>li").removeClass('active');
				$(".bestca ul").hide();
				$(this).addClass('active');
				$("#"+activeTab).fadeIn(250);
			});

			//2022-02-25 인플루언서몰 모바일 스크롤 하단 접촉 시 상품리스트 추가            
            let event_chk = 0;
			$(window).scroll(() => {
				if($(window).scrollTop() >= $(document).height() - $(window).height() - 100 && event_chk == 0){ 
                    let now_active = document.querySelector('.bestca_tab1 li.active').dataset.tab.substr(-1);
					if(now_active != 0) {
                        event_chk = 1;                        
						let now_ul = $(`#bstab_c${now_active}`);
						let last_item = now_ul.children('li').last()[0].classList[0];
						call_fetch('add_extra',{'last_item':last_item, 'active':now_active})
						.then((result) => {
							return result.text();
						}).then((result) => {
							let final_result = JSON.parse(result);
							for(let i=0; i < final_result.length; i++){
								now_ul.append(`
									<li class='${final_result[i].gcode}'>
										<div>
											<a href='${final_result[i].it_href}'>
												<dl>
													<dt>
														<img src = '${final_result[i].it_image}' alt = '상품이미지'>
													</dt>
													<dd class='pname'>${final_result[i].it_name}</dd>
													<dd class='price'>
														<p class="sale">${String(final_result[i].sale)}<span>${final_result[i].sale&&'%'}</span>
														</p>
														<span class='price_box'>${final_result[i].it_sprice}
															<span class='recommendation'>
																추천인할인가★
															</span>
															${final_result[i].it_price}
														</span>
													</dd>
												</dl>
											</a>
										</div>
									</li>
								`);
							}
						}).then(() => {
                            event_chk = 0;
                        });
					}
				}
			});
		});
		</script>
		<?php } ?>
		<!-- } 베스트상품(수동) 카테고리별 베스트 끝 -->
	<?php } ?>
</div>
<!-- } 베스트상품  끝 -->
