function generalSettingsForUserList(){
	//adds user indexes to user list on mainpage
	$('.user').each(function(k,v){
		status = $(this).find("[name='status']").attr('status');
		$indexElm = $(this).find('.user-index');
		$giftElm = $(this).find('.user-gift');
		
		$indexElm.text(k+1);
		$indexElm.addClass((status == '1') ? 'user-index-active' : 'user-index-passive');
		
		if(status == '1'){
			$giftElm.css("pointer-events", "auto").addClass('user-index-active');
		}else{
			$giftElm.css("pointer-events", "none").addClass('user-index-passive');
		}
	});
}

function showGiftsWindow(event){
	var target = event.target;
	var receiverUserId = parseInt($(target).parent().find('.user-id').html());
	$('#receiver-user-id').html(receiverUserId);
	$('#gift-window').show();
}

function hideGiftWindow(event){
	var target = event.target;
    if (!$(target).is('#gift-window') && !$(target).is('.user-gift') && !$(target).parents().is('#gift-window')) {
        $('#gift-window').hide();
    }
}

function sendGift(event){
	var target = event.target;
	var giftId = parseInt($(target).parent().find('.gift-id').html());
	var receiverUserId = parseInt($('#receiver-user-id').html());
	
	var values = {
		process: "sendgift",
		giftid: giftId,
		receiverUserId: receiverUserId
	};
	
	$.ajax({
        url: "ajax.php",
        type: "post",
        data: values,
        success: function (response) {
			alert(response);
        },
		error: function(jqXHR, textStatus, errorThrown) {
           alert('Error');
        }
    });
}

function acceptOrDismissGift(event){
	var target = event.target;
	var choice = parseInt($(target).attr('choice'));
	var giftListId = parseInt($(target).parent().find('.pending-gift-id').html());
	
	//choice 1 accept, 0 dismiss
	var values = {
		process: "acceptordismiss",
		giftListId: giftListId,
		choice: choice
	};
	
	$.ajax({
        url: "ajax.php",
        type: "post",
        data: values,
        success: function (response) {
			alert(response);
			if(response=='Accepted'){
				FB.ui({method: 'apprequests',
				  message: 'Please try this'
				});
			}
        },
		error: function(jqXHR, textStatus, errorThrown) {
           alert('Error');
        }
    });
	
}

$(function(){
	generalSettingsForUserList();
	$('.user-gift').on('click', showGiftsWindow);
	$('.gift-send-button').on('click', sendGift);
	$('*').on('click', hideGiftWindow);
	$('#pending-gifts .pending-gift-accept, #pending-gifts .pending-gift-dismiss').on('click', acceptOrDismissGift);
});






