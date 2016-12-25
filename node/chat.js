var WebSocketServer = require('ws').Server,
    wss = new WebSocketServer({ port: 1337 });

var nodeConfig = require('../config/node-config.js');

var http = require('http'),
	options = {
		host: nodeConfig.host,
		path: ''
	},
	callback;

var users = [];

wss.on('connection', function (ws) {
    ws.on('message', function (message) {
        message = JSON.parse(message);

        if (message.action == 'login') {
        	options.path = '/addtochat.php?id=' + message.id;

        	callback = function(response) {
				var token = '';

			    response.on('data', function (chunk) {
			  	    token += chunk;
			    });

			    response.on('end', function () {
			    	users.push({
		            	token: token,
		            	ws: ws
		            });

			    	ws.send(JSON.stringify({
			    		action: 'login',
			    		token: token
			    	}));
			    });
			}

			http.request(options, callback).end();
        } else if (message.action == 'logout') {
			options.path = '/removefromchat.php?token=' + message.token;

        	callback = function(response) {
				response.on('end', function () {
					for (var i = 0; i < users.length; ++i) {
		        		if (users[i].token == message.token) {
		        			users.splice(i, 1);
		        			break;
		        		}
		        	}
				});
			}

			http.request(options, callback).end();
        } else if (message.action == 'message') {
        	options.path = '/userbyguid.php?token=' + message.token;

        	callback = function(response) {
				var userName = '';

			    response.on('data', function (chunk) {
			  	    userName += chunk;
			    });

			    response.on('end', function () {
			    	for (var j = 0; j < users.length; ++j) {
		        		try {
			        		users[j].ws.send(JSON.stringify({
			                    action: 'message',
			                    userName: userName,
			                    text: message.text
			                }));
		        		}
		        		catch(e) {
		        			options.path = '/removefromchat.php?token=' + users[j].token;

		        			callback = function(response) {
							    response.on('end', function () {
							    	// ...
							    });
							}

							http.request(options, callback).end();

		        			users.splice(j, 1);
		        			--j;
		        		}
		        	}
			    });
			}

			http.request(options, callback).end();
        }
    });
});