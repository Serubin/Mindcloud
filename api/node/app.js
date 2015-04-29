var https = require('https'),
	fs = require('fs');

var options = {
    key: 	fs.readFileSync('/etc/apache2/ssl/mindcloud_io/mindcloud_io.key'),
    cert: 	fs.readFileSync('/etc/apache2/ssl/mindcloud_io/mindcloud_io.crt'),
    ca: 	fs.readFileSync('/etc/apache2/ssl/mindcloud_io/mindcloud_io.ca-bundle')
}

var serverForUI = https.createServer(options),
    redisAdapter = require('socket.io-redis'),
    io = require('socket.io')(serverForUI);

io.adapter(redisAdapter({ host: '127.0.0.1', port: 6379 }));
 
serverForUI.listen(8000);
