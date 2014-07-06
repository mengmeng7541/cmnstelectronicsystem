<!-- Modal -->
<div ng-controller="bootstrap_modal_controller">
<div id="info_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">訊息</h3>
    </div>
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-primary hide">OK</button>
    </div>
</div>
<div modal watch="$root.modal.info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">{{$root.modal.info.header.title}}</h3>
    </div>
    <div class="modal-body">
        <p><div class="alert alert-{{$root.modal.info.body.message}}"><strong>{{$root.modal.info.body.state|uppercase}}!</strong> {{$root.modal.info.body.message}}</div></p>
    </div>
    <div class="modal-footer" ng-show="$root.modal.info.footer">
		<a ng-href="{{button.link_url}}" data-dismiss="{{button.dismiss}}" class="btn btn-primary" ng-repeat="button in $root.modal.info.footer" ng-show="button.text != ''">{{button.text}}</a>
    </div>
</div>
<div id="form_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:640px;margin-left:-320px">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">表單</h3>
    </div>
    <div class="modal-body" style="max-height:none;">
        <p></p>
    </div>
    <div class="modal-footer">
		<button name="form_modal_submit" data-dismiss="modal" class="btn btn-primary">送出</button>
    </div>
</div>
</div>


   </div>
   <!-- END CONTAINER -->
   <!-- BEGIN FOOTER -->
   <div id="footer">
       2013-2014 &copy; CMNST E-System.
      <div class="span pull-right">
         <span class="go-top"><i class="icon-arrow-up"></i></span>
      </div>
   </div>
   <!-- END FOOTER -->
   <!-- BEGIN JAVASCRIPTS -->    
   
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="<?=base_url();?>assets/angular-1.2.17/angular.min.js"></script>
   
   <script src="<?=base_url();?>js/jquery.validate.js"></script>
   <script src="<?=base_url();?>assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
   
   
   <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="<?=base_url();?>assets/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
   <script src="<?=base_url();?>js/jquery.blockui.js"></script>
   <script src="<?=base_url();?>js/jquery.cookie.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="<?=base_url();?>js/excanvas.js"></script>
   <script src="<?=base_url();?>js/respond.js"></script>
   <![endif]-->
   
   
   <!--<script type="text/javascript" src="<?=base_url();?>assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>-->
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-chosen-1.1.0/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/data-tables/DT_bootstrap.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/FixedHeader-2.1.0/js/dataTables.fixedHeader.min.js"></script>

   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/clockface/js/clockface.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>   
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-daterangepicker/date.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-daterangepicker/daterangepicker.js"></script> 
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>  
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
   <script src="<?=base_url();?>assets/fancybox/source/jquery.fancybox.pack.js"></script>
   
   <script src="<?=base_url();?>js/jquery.form.js"></script>
   
   <!-- jquery slider -->
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/jshashtable-2.1_src.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/jquery.numberformatter-1.2.3.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/tmpl.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/jquery.dependClass-0.1.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/draggable-0.1.js"></script>
   <script type="text/javascript" src="<?=base_url();?>assets/jslider/js/jquery.slider.js"></script>
   <!-- end -->
   <script type="text/javascript" src="<?=base_url();?>js/moment-with-langs.min.js"></script>
   <script type="text/javascript" src="<?=base_url();?>js/jquery.jqprint-0.3.js"></script>
   <script type="text/javascript" src="<?=base_url();?>js/jquery.fittext.js"></script>
   <script type="text/javascript" src="<?=base_url();?>js/jquery.fullscreen-0.4.1.min.js"></script>
   
   <script src="<?=base_url();?>js/scripts.js?20140704"></script>
   <script src="<?=base_url();?>js/cmnst/cmnst-app.js"></script>
   
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.init();
      });
      
   </script>
   <!-- END JAVASCRIPTS -->   
</body>
<!-- END BODY -->
</html>