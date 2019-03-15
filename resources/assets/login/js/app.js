require('./bootstrap');

window.Vue = require('vue');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


const app = new Vue({
    el: '#app',
    created: function() {

    },
    methods: {
        formController: function (url, event) {
            let target = $(event.target);
            let fd = new FormData(event.target);

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
                        if (cont == 0) {
                            c_target.prev().focus();
                        }
                        c_target.prev().addClass('is-invalid');
                        c_target.html(item);
                        cont++;
                    });
                });
        },
        clearErrorMsg: function(event)
        {
            var elem = $(event.target);
            if(elem.hasClass('is-invalid'))
            {
                elem.removeClass('is-invalid');
                elem.next().html('');
            }
        },
        alertMsg: function(data)
        {
            if(data.type == 1){
                swal({
                    title: data.title,
                    text: data.msg,
                    icon: "success",
                    timer: 3000,
                }).then((confirmed) => {
                    location.reload();
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
            }
        }
    }
});
