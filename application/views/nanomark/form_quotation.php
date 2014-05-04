
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     奈米技術產品測試實驗室
                     <small>檢測服務系統</small>
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
                        <h4><i class="icon-reorder"></i>檢測服務報價表單</h4>
                    </div>
                     <div class="widget-body form">
					 	<div class="alert alert-block alert-info">
			    			<h4 class="alert-heading">國立成功大學 微奈米科技研究中心 奈米技術產品測試實驗室 檢測服務報價單</h4>
							<ol>
								<li>本單依指定之範圍估價，成約後如工作項目或數量有變更，須另收費用。 </li>
								<li>本估價單有效期為十五天，逾期自動銷案。 </li>
								<li>如蒙委託，請先付費用。 </li>
							</ol>
							抬頭：國立成功大學 <br>
							聯絡地址：701台南市大學路1號 <br>
							聯絡電話：06-2757575  許郁佩  分機31380 <br>
							傳真電話：06-2080103
			    		</div>  
<form id="form_nanomark_quotation" method="post" action="<?=empty($action)?"":$action;?>" class="form-horizontal">

<div class="print_area">
  <table id="table_nanomark_quotation" width="940" border="0">

	<thead>
    <tr>
      <th width="100" align="center">&nbsp;</th>
      <th width="350" align="center">&nbsp;</th>
      <th width="100" align="center">&nbsp;</th>
      <th width="350" align="center">&nbsp;</th>
    </tr>
    <tr>
      <th colspan="4" align="right">日期：<?php if(!empty($quotation_date)) echo $quotation_date; ?></th>
    </tr>
    <tr>
      <td  align="center">委託機構</td>
      <td ><input type="text" name="organization" value="<?=empty($organization)?"":$organization; ?>" class="span12"></td>
      <td align="center">委測者</td>
      <td ><input type="text" name="contact_name" value="<?=empty($contact_name)?"":$contact_name; ?>" class="span12"></td>
    </tr>
    <tr>
      <td  align="center">聯絡電話</td>
      <td width="133"><input type="text" name="contact_tel" value="<?=empty($contact_tel)?"":$contact_tel; ?>" class="span12"></td>
      <td align="center">傳真號碼</td>
      <td colspan="3"><input type="text" name="contact_FAX" value="<?=empty($contact_FAX)?"":$contact_FAX; ?>" class="span12"></td>
    </tr>
    <tr>
      <td  align="center">E-mail</td>
      <td colspan="3"><input type="text" name="contact_email" value="<?=empty($contact_email)?"":$contact_email; ?>" class="span12" /></td>
    </tr>
    <tr>
      <td  align="center">委託事項</td>
      <td colspan="3"><textarea name="entrust_item" class="span12" rows="5"><?=empty($entrust_item)?"":$entrust_item;?></textarea></td>
    </tr>

	</thead>
    <tbody>
		<?
		$tmpl = array ('table_open' => '<table id="table_nanomark_quotation_test_item" class="table table-bordered table-hover">');
		$this->table->set_template($tmpl);
		$this->table->set_heading(	array('data'=>'項次','width'=>'60'),
									'檢驗項目',
									array('data'=>'數量','width'=>'60'),
									array('data'=>'單價','width'=>'100'),
									array('data'=>'金額','width'=>'150'));
		
		if(!empty($data))
		{
			foreach($data as $row)
			{
				$this->table->add_row(	$row['index'].form_hidden("test_item_serial_no[]",
										$row['serial_no']),
										form_dropdown("test_item_name[]",$test_item_select_options,$row['test_item'],"class='span12 chosen'"),
										form_input("test_item_amount[]",$row['amount'],"class='input-mini'"),
										form_input("test_item_fees[]",$row['fees'],"class='input-mini'"),
										form_input("test_item_total_fees[]",$row['total_fees'],"class='input-mini' readonly='readonly'"));
			}
		}else{
			$this->table->add_row(	"1",
									form_dropdown("test_item_name[]",$test_item_select_options,"","class='span12 chosen'"),
									form_input("test_item_amount[]","","class='input-mini'"),
									$this->nanomark_model->is_application_case_officer_1st()?form_input("test_item_fees[]","","class='input-mini'"):"",
									$this->nanomark_model->is_application_case_officer_1st()?form_input("test_item_total_fees[]","","class='input-mini' readonly='readonly'"):"");
		}
		$this->table->add_row(	form_button("","新增","id='add_row_btn' class='btn btn-primary'"),
								"",
								"",
								"<strong>總金額(含稅)</strong>",
								$this->nanomark_model->is_application_case_officer_1st()?form_input("total_fees",isset($total_fees)?$total_fees:0,"class='input-medium' readonly='readonly'"):"");
		?>
		
		<tr>
			<td colspan="7">
				<?=$this->table->generate();?>
				<tr class='hide' id="new_row">
					<td align='center'>1</td>
					<td><?=form_dropdown("test_item_name[]",$test_item_select_options,"","class='span12'");?></td>
					<td align='center'><input type="text" name="test_item_amount[]" class="input-mini"></td>
					<td align='center'><input type="<?=$this->nanomark_model->is_application_case_officer_1st()?"text":"hidden";?>" name="test_item_fees[]" class="input-small"></td>
					<td align='right'><input type="<?=$this->nanomark_model->is_application_case_officer_1st()?"text":"hidden";?>" name="test_item_total_fees[]" class="input-medium" readonly="readonly"></td>
				</tr>
			</td>
		</tr>
		
    </tbody>
	<tfoot>
    <tr>
      <td align="center">備註</td>
      <td colspan="3"><textarea name="comments" class="span12" rows="5"><?=empty($comments)?"":$comments;?></textarea></td>
    </tr>
	</tfoot>
<input name="serial_no" type="hidden" value="<?php if(!empty($serial_no)) echo $serial_no; ?>">  

  </table>
</div>
	<div class="form-actions">
		<? if($this->nanomark_model->is_application_case_officer_1st()){ ?>
			<button type="submit" name="save" class="btn btn-warning">儲存</button>
			<a href="<?=$this->agent->referrer();?>" class="btn btn-danger">取消</a>
		<? } ?>
		<button type="submit" name="submit" class="btn btn-primary">送出</button>
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


