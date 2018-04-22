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
    console.log(socket);

});

io.on('connection', function (socket) {
    // socket.broadcast.emit('user connected');
    console.log('new connection');
    // socket.emit('customEmit', { hello: 'world' });
    // socket.emit('userMessage', { hello: 'world' });
    // socket.emit('user_message', { hello: 'world' });
    // socket.emit('socket_userMessage', { hello: 'world' });
    // socket.emit('/', { hello: 'world' });
    // socket.on('u.', function (data) {
    //     console.log(data);
    // });


});

http.listen(3000, function(){
    console.log('Listening on Port 3000');
});