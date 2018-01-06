var EstadaoAPI = {
    request_url: null,
    request_type: null,
    data: {},
    send: function(callback){
        callback = typeof callback === "function" ? callback : function(){};
        var this_main = this;

        var params = "";
        Object.keys(this.data).map(function(index) {
            params += "&"+index+"="+this_main.data[index]
        });

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == XMLHttpRequest.DONE) {
                callback(xhttp.responseText);
            }
        }
        xhttp.open(this_main.request_type, this_main.request_url+"?"+params, true);
        xhttp.send();
    },

    getListCarro: function(callback){
        callback = typeof callback === "function" ? callback : function(){};
        var api = EstadaoAPI;

        api.request_url = '/api/carros';
        api.request_type = 'GET';

        api.send(function(data){
            data = JSON.parse(data);
            callback(data);
        })

    }
};