
<div class="footer">
		<script src="<?php echo $js ?>jquery-1.12.1.min.js"></script>
		<script src="<?php echo $js ?>jquery-ui.min.js"></script>
		<script src="<?php echo $js ?>bootstrap.min.js"></script>
		<script src="<?php echo $js ?>jquery.selectBoxIt.min.js"></script>
		<script src="<?php echo $js ?>front.js"></script>


        </div>


		<script>



function checkFileds(target, length, ajaxLocation, btnId, errMsgId,phpInfos = {}) {
$(target).on('input', function() {
	if (this.value.length >= length) {
		$.post(ajaxLocation, {
			formType: 'CheckFree',
			fieldsValue: this.value,
			fieldName:phpInfos['fieldName'],
			tableName:phpInfos['tableName']
		}, (one, two, three) => {
			if (one == 1) {
				document.getElementById(errMsgId).removeAttribute('hidden');
				document.getElementById(btnId).setAttribute('disabled', '');
			} else {
				document.getElementById(errMsgId).setAttribute('hidden', '');
				document.getElementById(btnId).removeAttribute('disabled');

			}
		})
	} else {
		document.getElementById(errMsgId).setAttribute('hidden', '');
	}
})
}
</script>

	</body>
</html>