var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('quiz-app');
redis.on('message', function (channel, message) {
    console.log(message);
    const messageJson = JSON.parse(message);
    for (let user of messageJson.to) {
        console.log('Push message to ' + user)
        io.emit(user, messageJson);
    }
});

redis.on('connection', function(socket){

});

io.on('connection', function (socket) {
    console.log('new connection...')
    redis.incr('currentConnection');
    redis.incr('totalConnection')
    socket.on('disconnect', function () {
        redis.decr('currentConnection')
    });
});

io.on('disconnection', function (socket) {
    console.log('disconnect')
})

http.listen(3000, function(){
    console.log('Listening on Port 3000');
});

// 4a1cd73937065238307f510f2666da2b