</body>
<script src="<?php echo $js_directory; ?>jquery.js"></script>
<script src="<?php echo $js_directory; ?>bootstrap.min.js"></script>

<!-- [START] Functions -->
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
<!-- [START] Functions -->

</html>