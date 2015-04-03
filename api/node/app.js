var serverForUI = require('http').createServer(),
    redisAdapter = require('socket.io-redis'),
    io = require('socket.io')(serverForUI);
 
io.adapter(redisAdapter({ host: '127.0.0.1', port: 6379 }));
 
serverForUI.listen(8000);
