<template>
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-md-auto">
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Buscar por nombre"
                    v-model="name"
                    >
            </div>
            <div class="col-12 col-md-auto">
                <select class="form-control" v-model="role_id">
                    <option value="">Filtro por roles</option>
                    <option 
                        :value="item.id" 
                        v-for="(item) in $parent.$store.getters.getRoles" 
                        :key="item.id"
                    >
                        {{ item.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            urlRoles: {
                type: String,
                default: ""
            },
        },
        data(){
            return {
                name: "",
                role_id: ""
            }
        },
        computed: {

        },
        created() {
            this.getRoles();
            this.$parent.$on('refresh', function() {
                this.role_id = "";
            }.bind(this));
        },
        mounted() {
        },
        watch: {
            name: function(newValue,oldValue) {
                if(newValue.length > 3)
                    this.filterData();
            },
            role_id: function(newValue,oldValue) {
                this.filterData();
            },
        },
        methods: {
            getRoles: function()
            {
                this.$parent.$emit('loading', true);
                axios.post(this.urlRoles, {}).then(response => {
                    this.$parent.$emit('loading', false);
                    this.$parent.$store.commit('updateRoles',response.data.roles);
                }).catch(error => {
                    this.$parent.$emit('loading', false);
                });
            },
            filterData: function()
            {
                this.$parent.$emit('filterData',this.name,this.role_id)
            }
        },
    }
</script>
