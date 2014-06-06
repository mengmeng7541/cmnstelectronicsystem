<!-- BEGIN SIDEBAR -->
    <div id="sidebar" class="nav-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<ul class="sidebar-menu">
			<? if($this->session->userdata('ID')){ ?>
				<label>您好！<?php echo $this->session->userdata('ID'); ?> 先生/小姐</label>
			<?php } ?>
			
			<?php if($this->session->userdata('status')=="admin"){ ?>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 內部管理
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/admin/clock/list">中心人員所在位置</a></li>
	                    <li><a class="" href="<?=site_url();?>/user/clock/list/3">使用者所在位置</a></li>
						<li><a class="" href="<?=site_url();?>/admin/list">管理者資料維護</a></li>
						<li><a class="" href="<?=site_url();?>/user/list">使用者資料維護</a></li>
						<li><a class="" href="<?=site_url();?>/boss/list">老師主管資料維護</a></li>
						<li><a class="" href="<?=site_url();?>/org/list">公司行號資料維護</a></li>
	                </ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 儀器預約
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/facility/admin/available/list">儀器預約</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/booking/list">預約紀錄</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/facility/list">儀器管理</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/nocharge/list">預約不計費</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/privilege/list">使用者權限</a></li>
	                </ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 訓練課程
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<!--<li><a class="" href="<?=site_url();?>/curriculum/signature/list">線上簽到</a></li>-->
						<li><a class="" href="<?=site_url();?>/curriculum/class/list">開課列表</a></li>
						<li><a class="" href="<?=site_url();?>/curriculum/reg/list">學員列表</a></li>
						<li><a class="" href="<?=site_url();?>/curriculum/course/list">課程列表</a></li>
						<li><a class="" href="<?=site_url();?>/curriculum/config/form">系統設置</a></li>
	                </ul>
				</li>
				<li class="has-sub ">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 奈米標章
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/nanomark/list_quotation">報價單審核</a></li>
	                    <li><a class="" href="<?=site_url();?>/nanomark/list_application">委託單審核</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/list_outsourcing">外包同意書</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/list_customer_survey">滿意度問卷</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/list_report_revision">報告修改表</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/list_verification_norm">規範設定</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/list_test_item">檢測設定</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/edit_config">系統設定</a></li>
	                </ul>
				</li>
				<li class="has-sub ">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 論文獎勵
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/reward/list">論文獎勵審查</a></li>
	                    <li><a class="" href="<?=site_url();?>/reward/config/edit">系統設定</a></li>
	                </ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 門禁管制
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/access/card/application/temp/form">臨時卡申請</a></li>
						<li><a class="" href="<?=site_url();?>/access/card/application/temp/list">臨時磁卡核發</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/card/list">永久磁卡核發</a></li>
						<li><a class="" href="<?=site_url();?>/facility/admin/access/ctrl/list">卡機控管</a></li>
	                    <li><a class="" href="<?=site_url();?>/facility/admin/access/card/list">進出紀錄</a></li>
	                    <li><a class="" href="<?=site_url();?>/facility/admin/access/link/list">連線設定</a></li>
	                    <li><a class="" href="<?=site_url();?>/access/config/edit">系統設置</a></li>
					</ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 現金帳務
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/cash/curriculum/list">訓練課程繳費</a></li>
						<li><a class="" href="<?=site_url();?>/cash/nanomark/list">奈米標章繳費</a></li>
						<li><a class="" href="<?=site_url();?>/cash/receipt/list">收據列表</a></li>
						<li><a class="" href="<?=site_url();?>/cash/config/edit">系統設置</a></li>
					</ul>
				</li>
				<li><a class="" href="<?=site_url();?>/logout"><span class="icon-box"><i class="icon-user"></i></span> 登出</a></li>
	        <?php }else if($this->session->userdata('ID')){ ?>
				<li><a class="" href="<?=site_url();?>/user/edit"><span class="icon-box"><i class="icon-user"></i></span> 個人資料維護</a></li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 儀器預約系統
					<span class="arrow"></span>
					</a>
					<ul class="sub">
	                    <li><a class="" href="<?=site_url();?>/facility/user/available/list">預約儀器</a></li>
	                    <li><a class="" href="<?=site_url();?>/facility/user/booking/list">查詢紀錄</a></li>
	                    <li><a class="" href="<?=site_url();?>/facility/user/card/form">申請/退還磁卡</a></li>
	                </ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 課程報名系統
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/curriculum/class/list">一般課程報名</a></li>
						<li><a class="" href="<?=site_url();?>/curriculum/class/list/certification">認證課程報名</a></li>
						<li><a class="" href="<?=site_url();?>/curriculum/reg/list">選課紀錄</a></li>
	                </ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;" class="">
					<span class="icon-box"><i class="icon-cogs"></i></span> 奈米標章
					<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li><a class="" href="<?=site_url();?>/nanomark/form_quotation">報價諮詢</a></li>
	                    <li><a class="" href="<?=site_url();?>/nanomark/form_application">委託申請</a></li>
						<li><a class="" href="<?=site_url();?>/nanomark/form_report_revision">報告修改申請</a></li>
	                    <li><a class="" href="<?=site_url();?>/nanomark/list_progress">進度查詢</a></li>
	                </ul>
				</li>
				<li><a class="" href="<?=site_url();?>/reward"><span class="icon-box"><i class="icon-user"></i></span> 論文獎勵申請</a></li>
				<li><a class="" href="<?=site_url();?>/logout"><span class="icon-box"><i class="icon-user"></i></span> 登出</a></li>
			<?php }else{ ?>
				<li><a class="" href="<?=site_url();?>/user/form"><span class="icon-box"><i class="icon-user"></i></span> 申請新帳號</a></li>
				<li><a class="" href="<?=site_url();?>/reward"><span class="icon-box"><i class="icon-user"></i></span> 論文獎勵申請</a></li>
				<li><a class="" href="<?=site_url();?>"><span class="icon-box"><i class="icon-user"></i></span> 登入</a></li>
				<!--<li><a class="" href="<?=site_url();?>/"><span class="icon-box"><i class="icon-user"></i></span> 登入</a></li>-->
			<?php } ?>
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
<!-- END SIDEBAR -->