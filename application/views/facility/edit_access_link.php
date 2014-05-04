      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器預約系統
					 <small>卡機連線資訊管理</small>
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>卡機連線設定表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/facility/admin/access/link/<?=$action;?>" method="POST" class="form-horizontal">
                     		<div class="control-group">
                     			<label class="control-label">卡機編號</label>
                     			<div class="controls">
                     				<input type="text" name="CtrlNo" value="<?=empty($CtrlNo)?"":$CtrlNo;?>" <?=empty($CtrlNo)?"":"readonly='readonly'";?>/>
                     			</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">型態</label>
                     			<div class="controls">
                     				<label class="radio"><input type="radio" name="MType" value="1" <?=empty($MType)?"":$MType==1?"checked='checked'":"";?>/>門禁</label>
                     				<label class="radio"><input type="radio" name="MType" value="2" <?=empty($MType)?"":$MType==2?"checked='checked'":"";?>/>儀器</label>
                     			</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">IP位置</label>
                     			<div class="controls">
                     				<input type="text" name="Tcpip" value="<?=empty($Tcpip)?"":$Tcpip;?>" />
                     			</div>
                     		</div>
	                     	
	                     	<div class="form-actions">
	                     		<button type="submit" class="btn btn-warning">送出</button>
	                     		<a href="<?=site_url();?>/facility/admin/access/link/list" class="btn btn-primary">回上一頁</a>
	                     	</div>
                     	</form>
                     	
                     </div>
                  </div>
               </div>
			</div>
			
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

