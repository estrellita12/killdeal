<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="cont_wrap">

	<!-- ����Ʈ ����Ư�� �� ��� ���� { -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo display_banner(3, $pt_id); ?></div>
		<div class="bnr2"><?php echo display_banner(4, $pt_id); ?></div>
		<div class="bnr3"><?php echo display_banner(5, $pt_id); ?></div>
		<div class="best_rol_slide">
			<h2><?php echo $default['de_pname_1']; ?></h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="best_rol">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 190, 190);
					$it_name = cut_str($row['gname'], 100);
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (���߰� - �����ǸŰ�) / ���߰� X 100 = ���η�%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<dd class="sale">'.number_format($sett,0).'%</dd>';
						$it_sprice = display_price2($row['normal_price']);
					}
				?>
				<dl>
					<?php echo $sale; ?>
					<a href="<?php echo $it_href; ?>">
						<dt class="pimg"><?php echo $it_image; ?></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
					</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php if($type1_count) { ?>
		<script>
		$(document).ready(function(){
					
			$('.best_rol').slick({
				autoplay: true,
				dots: false
			});
  
           
		    //���븮��Ʈ xml read �׽�Ʈ_20190524
		    //pt_id=golf�ΰ�� ����ǰ� �ؾ���.
			
			$.ajax({
				      
		              url : 'https://giftdev.e-hyundai.com/hb2efront_new/pointOpenAPI.do',
					  //url : 'hprox.php', //crossDomain������ Prox������ ����ϴ� ������ ȣ��
		              type : 'GET', 
				      data : {
			                     mem_id : 'B856E909E78FDFA2690CD9705BFD4D92',
                                 shopevent_no : '73FB843F801B403D809F23C00A436192',
								 proc_code : 'FA156C018605778E',
								 chk_data : '8D7E3454D1BB9B54',
                                 media_cd : 'MW'
		                     },
				      dataType : 'XML',
		              success : function(result){  
							  
							  $(result).find('param').each(function(){  // xml ���� item �������� �и��� �ݺ�
                              var return_point = $(this).find("return_point").text();
							  //alert(return_point);
							  //html�������� ȸ���� ����Ʈ ����ϱ�
							  });							 

					  },      
					  error: function(xhr, status, error) {
			                 //alert(error);
		               }	
	        });
			
           
			
		});
		</script>
		<?php } ?>

		
	</div>
	<!-- } ����Ʈ ����Ư�� �� ��� �� -->
</div>

<!-- ī�װ��� ����Ʈ ���� {-->
<div class="cont_wrap">
	<?php
	if($default['de_maintype_best']) {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);
		$tab_width = (float)(100 / $list_count);
	?>
	<h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<ul class="bestca_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
	</ul>
	<div class="bestca">
		<?php echo get_listtype_cate($list_best, '209', '209'); ?>
	</div>
	<script>
	$(document).ready(function(){
		$(".bestca_tab>li:eq(0)").addClass('active');
		$("#bstab_c0").show();

		$(".bestca_tab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".bestca_tab>li").removeClass('active');
			$(".bestca ul").hide();
			$(this).addClass('active');
			$("#"+activeTab).fadeIn(250);
		});
	});
	</script>
	<?php } ?>

	<div class="wide_bn mart40"><?php echo display_banner(6, $pt_id); ?></div>
</div>
<!-- } ī�װ��� ����Ʈ �� -->

<!-- ����Ʈ��ǰ ���� {-->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $default['de_pname_2']; ?></span></h2>
	<?php echo get_listtype_skin("2", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } ����Ʈ��ǰ �� -->

<!-- �Ż�ǰ ���� { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $default['de_pname_3']; ?></span></h2>
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } �Ż�ǰ �� -->

<!-- ū ��� ��� �� ���� ���� { -->
<?php echo mask_banner(7, $pt_id); ?>
<!-- } ū ��� ��� �� ���� �� -->

<!-- �α��ǰ ���� { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $default['de_pname_4']; ?></span></h2>
	<?php echo get_listtype_skin("4", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } �α��ǰ �� -->

<!-- �߰� ��ʿ��� ���� { -->

<!-- } �߰� ��ʿ��� �� -->

<!-- ��õ��ǰ ���� { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $default['de_pname_5']; ?></span></h2>
	<?php echo get_listtype_skin("5", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } ��õ��ǰ �� -->
