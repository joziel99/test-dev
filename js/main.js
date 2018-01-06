var Site = {
    newCar: function(){
        this.modalFormShow('Novo Carro', '/api/carros','POST', {});
    },

    updateCar: function(element, values){
        this.modalFormShow('Editar Carro', '/api/carros/'+values.id, 'PUT', values);
    },

    showMessage: function(message, color, time){
        var element = document.createElement("div");
        element.className = 'alert-message-estadao '+color;
        element.innerHTML = message;
        document.querySelectorAll('body')[0].appendChild(element);
        setTimeout(function(){
            element.className = 'alert-message-estadao show '+color;
        },50);

        setTimeout(function(){
            element.className = 'alert-message-estadao hide '+color;
            setTimeout(function(){
                element.parentNode.removeChild(element);
            },500);
        },time);
    },

    loadMarcas: function(){
        var api = EstadaoAPI;

        api.request_url = '/api/marcas';
        api.request_type = 'GET';

        api.send(function(data){
            data = JSON.parse(data);
            document.querySelectorAll('.form-modal select[name="brand"]')[0].innerHTML = '';
            data.sort(function(a, b) {
                var x=a.name.toLowerCase(),
                    y=b.name.toLowerCase();
                return x<y ? -1 : x>y ? 1 : 0;
            });
            for(var index in data) {
                var element = document.createElement("option");
                element.value = data[index].name;
                element.innerHTML = data[index].name;
                element.setAttribute('data-id', data[index].id);
                document.querySelectorAll('.form-modal select[name="brand"]')[0].appendChild(element);
            }

            var element = document.createElement("option");
            element.value = 'OUTROS';
            element.innerHTML = 'OUTROS';
            element.setAttribute('data-id', '261');
            document.querySelectorAll('.form-modal select[name="brand"]')[0].appendChild(element);

        });

        document.querySelectorAll('.form-modal select[name="brand"]')[0].onchange = function(){
            Site.loadMarcasModel(this.querySelectorAll('[value="'+this.value+'"]')[0].getAttribute('data-id'));
        }
    },

    loadMarcasModel: function(id , callback){
        callback = typeof callback === "function" ? callback : function(){};
        var api = EstadaoAPI;

        api.request_url = '/api/modelos/'+id;
        api.request_type = 'GET';

        document.querySelectorAll('.form-modal select[name="model"]')[0].style.opacity = '0.5';

        api.send(function(data){
            data = JSON.parse(data);

            document.querySelectorAll('.form-modal select[name="model"]')[0].innerHTML = '';
            document.querySelectorAll('.form-modal select[name="model"]')[0].style.opacity = '1';
            data.sort(function(a, b) {
                var x=a.name.toLowerCase(),
                    y=b.name.toLowerCase();
                return x<y ? -1 : x>y ? 1 : 0;
            });
            for(var index in data) {
                var element = document.createElement("option");
                element.value = data[index].name;
                element.innerHTML = data[index].name;
                document.querySelectorAll('.form-modal select[name="model"]')[0].appendChild(element);
            }

            var element = document.createElement("option");
            element.value = 'OUTROS';
            element.innerHTML = 'OUTROS';
            document.querySelectorAll('.form-modal select[name="model"]')[0].appendChild(element);

            callback();

        });
    },

    save: function(){
        var save_old = document.querySelectorAll('.form-modal .save')[0].getAttribute('data-save');
        if(save_old == 'true'){
            return;
        }
        document.querySelectorAll('.form-modal .save')[0].setAttribute('data-save', 'true');

        var data = {};
        for (var i = 0, element; element = document.querySelectorAll('.form-modal [name]')[i]; i++) {
            data[element.getAttribute('name')] = element.value;
        }

        var api = EstadaoAPI;

        api.request_url = data['url'];
        api.request_type = data['type'];
        delete data['url'];
        delete data['type'];
        api.data = data;

        api.send(function(data){
            data = JSON.parse(data);
            if(data.status == "success"){
                Site.updateCarList.init();
                Site.modalClose();
                Site.showMessage("<i class='icon-ok-circled'></i> Salvo com sucesso!", 'green', 5000);
                document.querySelectorAll('.form-modal .save')[0].removeAttribute('data-save');
            }else if(data.status == 'error'){
                document.querySelectorAll(".form-modal [name='"+data.error.field+"']")[0].focus();
                Site.showMessage("<i class='icon-attention'></i> "+data.error.message, 'red', 5000);
                document.querySelectorAll('.form-modal .save')[0].removeAttribute('data-save');
            }
        });
    },

    deleteCar: function(element, id){
        element.parentNode.parentNode.style.opacity = 0.3

        var api = EstadaoAPI;

        api.request_url = '/api/carros/'+id;
        api.request_type = 'DELETE';

        api.send(function(data){
            data = JSON.parse(data);
            if(data.status == "success"){
                Site.updateCarList.init();
                Site.modalClose();
                Site.showMessage("<i class='icon-ok-circled'></i> Deletado com sucesso!", 'green', 5000);
            }else if(data.status == 'error'){
                document.querySelectorAll(".form-modal [name='"+data.error.field+"']")[0].focus();
                Site.showMessage("<i class='icon-attention'></i> "+data.error.message, 'red', 5000);
            }
        });
    },


    modalFormShow: function(title, url, type ,values){
        document.querySelectorAll('.form-modal .title')[0].innerHTML = title;

        for (var i = 0, element; element = document.querySelectorAll('.form-modal [name]')[i]; i++) {
            element.value = '';
        }
        for(var index in values) {
            if( document.querySelectorAll('.form-modal [name="'+index+'"]').length){
                document.querySelectorAll('.form-modal [name="'+index+'"]')[0].value = values[index];
            }
        }

        document.querySelectorAll('.form-modal [name="url"]')[0].value = url;
        document.querySelectorAll('.form-modal [name="type"]')[0].value = type;

        var brand = document.querySelectorAll('.form-modal select[name="brand"]')[0];
        if(brand.options[brand.selectedIndex]){
            Site.loadMarcasModel(brand.options[brand.selectedIndex].getAttribute('data-id'), function () {
                if(("model" in values)){
                    document.querySelectorAll('.form-modal [name="model"]')[0].value = values['model'];
                }
            });
        }

        this.modalOpen();
    },

    modalOpen: function(){
        document.getElementById("form-modal").style.display = "inline-block";
        setTimeout(function(){
            document.getElementById("form-modal").style.opacity = "1";
        },50);
    },

    modalClose: function(){
        document.getElementById("form-modal").style.opacity = "0";
        setTimeout(function(){
            document.getElementById("form-modal").style.display = "none";
            for (var i = 0, element; element = document.querySelectorAll('.form-modal [name]')[i]; i++) {
                element.value = '';
            }
        },300);
    },

    modalConfirmOpen: function(confirm_callback){
        if(typeof confirm_callback !== "function"){
            return;
        }
        document.getElementById("confirm-modal").style.display = "inline-block";
        setTimeout(function(){
            document.getElementById("confirm-modal").style.opacity = "1";
        },50);

        document.getElementById('confirm-modal').querySelectorAll('.confirm')[0].onclick = function(){
            confirm_callback();
            Site.modalConfirmClose();
        }
    },

    modalConfirmClose: function(){
        document.getElementById("confirm-modal").style.opacity = "0";
        setTimeout(function(){
            document.getElementById("confirm-modal").style.display = "none";
        },300);
    },

    updateCarList: {
        init: function(){
            Site.updateCarList.clear();
            EstadaoAPI.getListCarro(function(data){
                if(data.status == "success"){

                    if(data.return.length > 0){
                        document.querySelectorAll('.car-table .no-fields')[0].style.display = 'none';
                        for(var index in data.return) {
                            Site.updateCarList.addItem(data.return[index]);
                        }
                    }else{
                        document.querySelectorAll('.car-table .no-fields')[0].style.display = 'inline-block';
                    }

                }
            });
        },
        addItem: function(item){
            var empty = document.querySelectorAll('.list-car-inner .empty')[0].cloneNode(true);
            empty.setAttribute('class','');
            empty.style.display = '';
            empty.querySelectorAll('.update')[0].setAttribute('onclick', "Site.updateCar(this,"+JSON.stringify(item)+")");
            empty.querySelectorAll('.delete')[0].onclick = function(){
                Site.modalConfirmOpen(function(){
                    Site.deleteCar(empty.querySelectorAll('.delete')[0],item.id);
                });
            }
            for(var index in item) {
                if(empty.querySelectorAll('[data-name="'+index+'"]').length){
                    empty.querySelectorAll('[data-name="'+index+'"] p')[0].innerHTML = item[index]
                }
            }
            document.querySelectorAll(".list-car-inner")[0].appendChild(empty);

        },
        clear: function(){
            var empty = document.querySelectorAll('.list-car-inner .empty')[0].cloneNode(true);
            document.querySelectorAll('.list-car-inner')[0].innerHTML = "";
            document.querySelectorAll(".list-car-inner")[0].appendChild(empty);
        }

    },
};

