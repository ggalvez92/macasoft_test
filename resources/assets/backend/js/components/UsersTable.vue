<template>
    <div class="container">
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Roles</th>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in users" :key="item.id">
                        <td>{{ item.n }}</td>
                        <td>{{ item.roles_list }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>
                            <img :src="item.url_image" v-if="item.url_image != ''">
                            <span v-if="item.url_image == ''">-</span>
                        </td>
                        <td>
                            <span>
                                <i class="far fa-edit" @click="$parent.openModal(item.id)"></i>
                            </span>
                            <span>
                                <i class="far fa-trash-alt" @click="deleteUser(item.id)"></i>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <nav aria-label="Paginación productos">
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#" aria-label="Anterior" @click.prevent="changePage(pagination.current_page - 1)">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Anterior</span>
                    </a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" v-bind:class="[page == isActived ? 'active' : '']">
                    <a class="page-link" href="" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#" aria-label="Siguiente" @click.prevent="changePage(pagination.current_page + 1)">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        props: {
            url: {
                type: String,
                default: ""
            },
            urlDelete: {
                type: String,
                default: ""
            }
        },
        data(){
            return {
                users: [],
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0
                },
                offset: 3,
            }
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        created() {
            this.$parent.$on('refresh', function(){
                this.getUsers();
            }.bind(this));

            this.$parent.$on('filterData', function(name,role_id){
                this.getUsers(1,name,role_id);
            }.bind(this));
        },
        mounted() {
            this.getUsers();
        },
        methods: {
            deleteUser: function(id) 
            {
                let data = {
                    type : 4,
                    title: "¿Estás seguro?",
                    msg: "Una vez eliminado no se podrá recuperar",
                    url: this.urlDelete,
                    id: id
                };
                
                this.$parent.alertMsg(data);
            },
            getUsers(page, name, role_id) 
            {
                this.$parent.$emit('loading', true);
                let params = {
                    page: page,
                    name: name,
                    role_id: role_id,
                }
                axios.post(this.url, params).then(response => {
                    this.$parent.$emit('loading', false);
                    this.users = response.data.users;
                    this.pagination = response.data.pagination;
                }).catch(error => {
                    this.$parent.$emit('loading', false);
                });
            },
            changePage: function(page) {
                this.pagination.current_page = page;
                this.getUsers(page);
            }
        },
    }
</script>
