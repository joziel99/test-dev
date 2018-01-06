var EstadaoAPI = {
    request_url: null,
    email: null,
    request_type: null,
    data: {},
    send: function(){
        var this_main = this;
        var xhttp = new XMLHttpRequest();
        Object.keys(this.data).map(function(index) {
            params += "&"+index+"="+this_main.data[index]
        });

        xhttp.open(this.request_type, this.request_url+"?"+params, true);
        xhttp.send();
    },
};