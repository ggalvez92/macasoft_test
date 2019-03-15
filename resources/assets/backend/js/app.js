require('./bootstrap');

window.Vue = require('vue');
import store from './store.js';

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));



const app = new Vue({
    el: '#app',
    created: function() {

    },
    store,
    methods: {
        openModal: function(id) {
            this.$emit('userModal', id);
        },
        formController: function (url, event, extra_info = null) {
            var target = $(event.target);
            var url = url;
            var fd = new FormData(event.target);
            if (extra_info != null)
                fd.append('extra_info', JSON.stringify(extra_info));

            this.$emit('loading', true);
            axios.post(url, fd,
                {
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded',
                    }
                }).then(response => {

                    this.$emit('loading', false);
                    this.alertMsg(response.data);
                }).catch(error => {
                    this.$emit('loading', false);
                    var obj = error.response.data.errors;
                    var cont = 0;
                    $.each(obj, function (i, item) {
                        let c_target = target.find("." + i + "-error");

                        c_target.each(function(index,elem){
                            let e = $(elem);
                            if (cont == 0) {
                                e.prev().focus();
                            }

                            let prev = e.prev();
                            prev.addClass('is-invalid');
                            e.html(item);
                            cont++;

                            if(e.attr('type') == "checkbox")
                            {
                                e.parent().addClass('was-validated');
                            }
                        });
     
                    });
                });
        },
        clearErrorMsg: function(event)
        {
            let elem = $(event.target);
            if(elem.hasClass('is-invalid'))
            {
                elem.removeClass('is-invalid');
                elem.next().html('');
            }
        },
        clearChkErrorMsg: function(event)
        {
            let elem = $(event.target).next();
            if(elem.hasClass('is-invalid'))
            {
                elem.removeClass('is-invalid');
                elem.next().html('');
            }
        },
        alertMsg: function(data)
        {
            let t = this;
            if(data.type == 1){
                swal({
                    title: data.title,
                    text: data.msg,
                    icon: "success",
                    timer: 3000,
                }).then((confirmed) => {
                    t.$emit("refresh");
                })
            } else if(data.type == 2)
            {
                swal({
                    title: data.title,
                    text: data.msg,
                    icon: "warning",
                })
            } else if(data.type == 3){
                swal({
                    title: data.title,
                    text: data.msg,
                    icon: "success",
                    timer: 2000,
                }).then((confirmed) => {
                    window.location = data.url;
                })
            } else if (data.type == 4) {
                swal({
                    title: data.title,
                    text: data.msg,
                    icon: "warning",
                    buttons: {
                        cancel: "Cancelar!",
                        catch: {
                            text: "Sí, eliminar!",
                            value: true,
                        },
                    },
                }).then(function (result) {
                    if (result) {
                        axios.post(data.url, { id: data.id } ).then(response => {
                            swal({
                                title: response.data.title,
                                text: response.data.msg,
                                icon: "success",
                                confirmButtonText: "OK",
                                timer: 1200,
                            }).then(() => {
                                t.$emit("refresh", false);
                            })

                        }).catch(error => {
                        });

                    }
                });
            }
        }
    }
});
