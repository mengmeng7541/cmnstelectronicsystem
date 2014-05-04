
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
                        <h4><i class="icon-reorder"></i>顧客滿意度調查表</h4>
                    </div>
                     <div class="widget-body form">
					 
<form id="form_customer_survey" method="post" action="<?=site_url();?>/nanomark/add_customer_survey/" class="form-horizontal">
<div class="print_area">
	<table width="940" border="0" cellspacing="1" cellpadding="1">
	<style>
	table table{
		border-top:double thick;
		border-bottom:double thick;
	}
	table table td{
		border-top:solid thin;
		border-bottom:solid thin;
	}
	</style>
	  <tr>
	    <td width="140"></td>
	    <td width="330"></td>
	    <td width="286"></td>
	    <td width="184"></td>
	  </tr>
	  <tr>
	    <td align="right"><img src="/img/CMNST.png" alt="" name="cmnst_logo" width="80" height="80" id="cmnst_logo" /></td>
	    <td colspan="3"><h2>顧客滿意度調查表 <strong>Customer Survey Form</strong></h2></td>
	  </tr>
	  <tr>
	    <td>客 戶 名 稱：<br />
	    Customer  Name：</td>
	    <td><?php echo $company_name; ?></td>
	    <td align="right">報告編號：<br />
	    Report Number：</td>
	    <td><input type="hidden" name="application_ID" value="<?php echo $application_ID; ?>"/><?php echo $application_ID; ?></td>
	  </tr>
	  <tr>
	    <td colspan="4"><br /><p>
	      您的意見對於提高我們的服務質素非常重要。您只需用少許時間來填寫本表格，然後轉交給我們便可。謝謝！ <br />
	    Your advice is very important to us. In order to serve you  better  and improve our quality,  please take a few minutes to  fill  the  following form, and return to our office. Thanks very much!</p>
	    <p>傳真 <strong>Fax: 06-2080103, </strong>電郵 <strong>E-mail:</strong> nanomark@mail.mina.ncku.edu.tw <strong></strong><br />
	    地址<strong> Address:</strong>台南市大學路一號<strong> 成功大學微奈米科技研究中心 奈米技術產品測試實驗室</strong></p></td>
	  </tr>
	  <tr>
	    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <th width="5%" align="center">&nbsp;</th>
	        <th width="35%">&nbsp;</th>
	        <th width="12%">非常好(5)<br />Excellent</th>
	        <th width="12%">好(4)<br />
	          Exceeded<br />
	          Expectations</th>
	        <th width="12%">一般(3)<br />
	          Met<br />
	          Expectations</th>
	        <th width="12%">差(2)<br />
	          Below <br />
	          Expectations</th>
	        <th width="12%">非常差(1)<br />
	          Poor</th>
	      </tr>
	      <tr>
	        <td align="center">1</td>
	        <td>我們整體的服務水平<br />
	          Overall quality of the service provided</td>
			<?php echo $overall_quality; ?>
	        
	      </tr>
	      <tr>
	        <td align="center">2</td>
	        <td>工作申請表<br />
	          Work Request form</td>
			<?php echo $request_form; ?>
	        </tr>
	      <tr>
	        <td align="center">3</td>
	        <td>解決您所提出的問題<br />
	          Our response to your questions</td>
			<?php echo $response_question; ?>
	        </tr>
	      <tr>
	        <td align="center">4</td>
	        <td>與我們實驗室職員的溝通<br />
	          Communications with our staff</td>
			<?php echo $communication; ?>
	        
	        </tr>
	      <tr>
	        <td align="center">5</td>
	        <td>測試所需時間能否合乎您的需要<br />
	          Time consuming for testing<br />
	          (Relating to your needs)</td>
			<?php echo $time_test; ?>
	        
	        </tr>
	      <tr>
	        <td align="center">6</td>
	        <td>測試報告證書上所叙述的資料<br />
	          Information presented in our Test Report  certificate</td>
			<?php echo $report; ?>
	        
	        </tr>
	      <tr>
	        <th align="center">&nbsp;</th>
	        <th>&nbsp;</th>
	        <th>非常便宜(5)<br />
	          Very<br />
	          inexpensive</th>
	        <th>便宜(4)<br />
	          inexpensive</th>
	        <th>合適(3)<br />
	          Reasonable</th>
	        <th>昂貴(2)<br />
	          Expensive</th>
	        <th>非常昂貴(1)<br />
	          Very<br />
	          Expensive</th>
	        </tr>
	      <tr>
	        <td align="center">7</td>
	        <td>測試費用<br />
	          Test price</td>
			<?php echo $price; ?>
	        
	        </tr>
	    </table></td>
	  </tr>
	  <tr>
	    <td colspan="4">如果您在上述第一至第七項選擇是“差＂或“非常差＂ 的話， 請說明原因：<br />
	    If your answer was  “Below Expectations” or “Poor” in any of  the above questions (item 1 to 7),  please state the reasons:</td>
	  </tr>
	  <tr>
	    <td colspan="4"><?php echo $reason; ?></td>
	  </tr>
	  <tr>
	    <td colspan="4">對於我們的服務有什麼建議和期望 Any recommendation for improvement of our service:</td>
	  </tr>
	  <tr>
	    <td colspan="4">
	      <?php echo $recommendation; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>填表人(隨意):<br />
	      Survey completed  by :
	(Optional)</td>
	    <td><?php echo $completed_by; ?></td>
	    <td><p>很榮幸能有機會為您提供服務！<br />
	    Thank you for the  opportunity to serve you!</p>
	    <p align="right"><u>張志欽</u><br />
	    <u>Chih-Ching  Chang</u></p></td>
	    <td><p align="right">&nbsp;</p></td>
	  </tr>
	  <tr>
	    <td>日期 Date:</td>
	    <td><?php echo $completed_date; ?></td>
	    <td colspan="2"><p>成大微奈米科技研究中心/奈米技術產品測試實驗室 <br />
	      Measurement  Laboratory for Nano Product</p>
	Center for Micro/Nano Science and Technology, NCKU</td>
	  </tr>
	  <tr>
	    <td colspan="4"><h6>DQP-04-02 V1.3</h6></td>
	  </tr>
	</table>
</div>
	<div class="form-actions">
	   <?php echo $action_btn; ?>
	   
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


