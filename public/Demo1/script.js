let Demo = {
    channel: 'test',
    client: null,
    config: {
        host: 'localhost',
        port: '1884',
        protocol: 'ws',
        clientId: 'FocusTestWeb1',
        auth: {
            username: 'user',
            password: 'password',
        }
    },
    init: function(){
        const config = this.config;
        const serverAddr = config.protocol+'://'+config.host+':'+config.port;
        this.client = mqtt.connect(serverAddr, {
            clientId: config.clientId,
            username: config.auth.username,
            password: config.auth.password,
            rejectUnauthorized: false,
        });
        this.client.on("message", this.onMessage);
        this.client.subscribe(this.channel);
    },
    onMessage: function (topic, payload) {
        jQuery('#canvas').append('<div>' + topic + ': ' + payload.toString() + '</div>');
    }
};
