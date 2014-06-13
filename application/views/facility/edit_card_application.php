<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN ADVANCED TABLE widget-->
        <div class="row-fluid">
            <div class="span12">
				<h3 class="page-title">儀器預約系統
				<small>磁卡管理</small>
				</h3>
                <!-- BEGIN EXAMPLE TABLE widget-->
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>磁卡申請表單</h4>
                    </div>
                    <div class="widget-body form">
						<form action="<?=site_url("/facility/user/card/add");?>" id="form_card_application" class="form-horizontal" method="POST">
							<div class="alert alert-info">
								<h4 class="alert-heading">辦理門禁磁卡注意事項</h4>
								<br />1. 請確實填寫完整資料
								<br />2. 通知領卡後請攜帶大頭照、500元押金至微奈米中心領卡
								<br />3. 若要辦理退卡，請親自攜帶卡片至微奈米中心辦理並領取押金500元
							</div>
							<div class="control-group ">
							   <label class="control-label"></label>
					           <div class="controls">
							      <select name="type">
							         <option value="apply">申請磁卡</option>
							         <option value="refund">退還磁卡</option>
							         <option value="reissue">申請補發</option>
							      </select>
					           </div>
							</div>
							<div class="control-group hide">
							   <label class="control-label">退還卡號</label>
					           <div class="controls">
							      <?=form_dropdown("card_num",isset($refundable_card_nums)?$refundable_card_nums:array(),"");?>
					           </div>
							</div>
							<div class="control-group ">
							   <label class="control-label">備註(如退卡請填寫原因)</label>
					           <div class="controls">
							      <input name="comment" type="text" />
					           </div>
							</div>
							<div class="control-group ">
					           <label class="control-label">申請日期</label>
					           <div class="controls">
							      <input name="application_date" type="text" value="<?=empty($application_date)?date("Y-m-d"):$application_date;?>" disabled="disabled"/>
					           </div>
					        </div>
							<div class="control-group ">
					           <label class="control-label">帳號</label>
					           <div class="controls">
							      <input name="ID" type="text" value="<?=$ID;?>" disabled="disabled"/>
					           </div>
					        </div>
							
							<div class="control-group">
					           <label class="control-label">姓名</label>
					           <div class="controls">
							      <input name="name" type="text" value="<?=$name;?>" disabled="disabled"/>
					           </div>
					        </div>
							<div class="control-group">
					           <label class="control-label">E-mail</label>
					           <div class="controls">
							      <input name="name" type="text" value="<?=$email;?>" disabled="disabled"/>
					           </div>
					        </div>
							<div class="control-group">
					           <label class="control-label">聯絡電話</label>
					           <div class="controls">
							      <input name="mobile" type="text" value="<?=$mobile;?>" disabled="disabled"/>
					           </div>
					        </div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">確認申請</button>
								<a href="<?=site_url();?>/user/edit" class="btn btn-warning">修改資料</a>
							</div>
						</form>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE widget-->
            </div>
        </div>
        <!-- END ADVANCED TABLE widget-->
    </div>
</div>
			



