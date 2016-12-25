function initChat(userId) {
	var chatButton = $('#chat-button');
	chatButton.click(function(e) {
		e.preventDefault();
		$('#chat-layer').show();
		return false;
	})

	var closeChatButton = $('#close-chat-layer');
	closeChatButton.click(function(e) {
		e.preventDefault();
		$('#chat-layer').hide();
		return false;
	});

	var chatInput = $('#chat-input');
	chatInput.val('');

	var ws = new WebSocket(window.chatServer);

	ws.onopen = function() {
        ws.send(JSON.stringify({
            action: 'login',
            id: userId
        }));
    }

    ws.onmessage = function (message) {
        message = JSON.parse(message.data);
        
        if (message.action == 'message') {
        	var messageDiv = $(document.createElement('div')).addClass('message-div');
        	var messageUserName = $(document.createElement('span')).text(message.userName);
        	var messageText = $(document.createElement('span')).text(message.text);
        	messageDiv.append(messageUserName);
        	messageDiv.append(messageText);
        	$('#chat-layer-text').append(messageDiv);
        } else if (message.action == 'login') {
        	window.chatToken = message.token;
        }
    }

	chatInput.keyup(function(e) {
		if (e.which == 13) {
			e.preventDefault();

			ws.send(JSON.stringify({
				token: window.chatToken,
			    action: 'message',
			    text: $(this).val()
			}));

			$(this).val('');
			return false;
		}
	});
}