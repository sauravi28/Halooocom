<?php if($errors) {?>
	<span class="obe_error">
		<?php echo _('Please correct the following errors:')?>
		<?php echo ul($errors);?>
	</span>
<?php } ?>
<html>
<head>
<style>
.btn {
  background-color: #1a2b6d;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
}

/* Darker background on mouse-over */
.btn:hover {
  background-color:#FFA500
}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</style>
</head>
<body>       
  <br/>
	<br/>
<div id="login_form">
	<form id="loginform" method="post" role="form">
		<h3><?php echo _('To get started, please enter your credentials:')?></h3>
		<div class="form-group">
			<input type="text" name="username" class="form-control" value="" placeholder="username" autocomplete="off">
		</div>
		<div class="form-group">
			<input type="password" name="password" class="form-control" value="" placeholder="password" autocomplete="off">
		</div>
	</form>
</div>
<?php
	if (\FreePBX::Modules()->checkStatus('pbxmfa') && $PBXMFA_LICENSED) {
		$webrootpath = \FreePBX::Config()->get('AMPWEBROOT');
		include $webrootpath . '/admin/modules/pbxmfa/views/mfa/otpModal.php';
	}
?>

<div align = "center" style = "position: fixed;top:120px;left:616px">
		 <span><img src="images/logo1.png" alt="logo" height = "122" width = "242" /></span>
		</div>
		<br/>
	<br/>
	<br/>
	<br/>
	
	
<div id="login_icon_holder">
	<!--<div class="login_item_title">
		<a href="#" class="login_item" id="login_admin" style="background-image: url(assets/images/sys-admin.png);"/>&nbsp;</a>
		<span class="login_item_text" style="display: block;width: 160px;text-align: center;">
			<?php echo _('Administration')?>
		</span>
	</div>-->
	
	<div class="login_item_title">
		<a href="#" class="login_item" id="login_admin"/><button class="btn"><i class="fa fa-home">  Administration</i></button></a>
		<span class="login_item_text" style="display: block;width: 160px;text-align: center;">
		</span>
	</div>
	
	<div class="login_item_title">
		<a href="/ucp" class="login_item" id="login_ari"/><button class="btn"><i class="fa fa-users">  User Control Panel</i></button></a>
		<span class="login_item_text" style="display: block;width: 160px;text-align: center;">
			<?php //echo _('User Control Panel')?>
		</span>
	</div>
	<?php /*if($panel) {?>
		<div class="login_item_title">
			<a href="<?php echo $panel?>" class="login_item" id="login_fop" style="background-image: url(assets/images/operator-panel.png);"/>&nbsp;</a>
			<span class="login_item_text" style="display: block;width: 160px;text-align: center;">
				<?php echo _('Operator Panel') ?>
			</span>
		</div>
	<?php } */?>
	<!--<div class="login_item_title">
		<a href="https://support.sangoma.com/" target="_blank" class="login_item" id="login_support" style="background-image: url(assets/images/support.png);"/>&nbsp;</a>
		<span class="login_item_text" style="display: block;width: 160px;text-align: center;">
			<?php //echo _('Get Support') ?>
		</span>
	</div>-->
	
	<div align = "center" style = "position: fixed;top:400px;left:616px">
		 <span><img src="images/logo-text.png" alt="logo" height = "122" width = "242" /></span>
		</div>

	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<div id="key" style="color: white;font-size:small">
		<?php //echo session_id();?>
	</div>
</div>
<script type="text/javascript" src="assets/js/views/login.js"></script>
<?php
	/*if (\FreePBX::Modules()->checkStatus('userman')) {
?>
	<script type="text/javascript" src='/admin/modules/userman/assets/js/adminPwdExpReminder.js'></script>
<?php
	}*/
?>

</body>
</html>