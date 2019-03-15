<template>
    <modal 
        id="userModal" 
        name="userModal" 
        transition="pop-out" 
        :width="modalWidth" 
        :minHeight="400" 
        height="auto" 
        :scrollable="true"
        :adaptive="true"
        @before-open="beforeOpen"
        @closed="closed"
        >
        <div class="close_btn" @click="$modal.hide('userModal')">
            <img :src="closeImg">
        </div>
        <div class="user-container" v-if="user.roles_list.length > 0">
            <form @submit.prevent="$parent.formController(url,$event)">
                <input type="hidden" name="id" :value="user.id">
                <div class="form-group">
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control form-control-sm" 
                        placeholder="Nombre completo"
                        autocomplete="off"
                        v-model="user.name"
                        v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
                    >
                    <div class="invalid-feedback name-error"></div>

                </div>
                <div class="form-group">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control form-control-sm" 
                        placeholder="Correo"
                        autocomplete="off"
                        v-model="user.email"
                        v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
                    >
                    <div class="invalid-feedback email-error"></div>
                </div>
                <div class="form-group">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control form-control-sm" 
                        placeholder="Clave"
                        autocomplete="off"
                        v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
                    >
                    <div class="invalid-feedback password-error"></div>
                </div>
                <div class="form-group">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control form-control-sm" 
                        placeholder="Confirmar clave"
                        autocomplete="off"
                        v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
                    >
                    <div class="invalid-feedback password_confirmation-error"></div>
                </div>
                <div class="form-group">
                    <input 
                        type="file" 
                        name="image" 
                        class="form-control form-control-sm" 
                        placeholder="Foto"
                        autocomplete="off"
                        v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
                    >
                    <div class="invalid-feedback image-error"></div>
                </div>
                <div 
                    class="was-validated custom-control custom-checkbox mb-2" 
                    v-for="(item,index) in $parent.$store.getters.getRoles" 
                    :key="item.id"
                >
                    <input type="checkbox" 
                            name="roles[]" 
                            :value="item.id" 
                            class="custom-control-input" 
                            :id="'customControlValidation'+index"
                            v-model="user.roles_list[index].value"
                            v-on="{ click: $parent.clearChkErrorMsg, focusin: $parent.clearChkErrorMsg }"
                            >
                    <label class="custom-control-label" :for="'customControlValidation'+index">{{ item.name }}</label>
                    <div class="invalid-feedback roles-error" type="checkbox"></div>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm" v-if="current_user_id == null">Registrar</button>
                    <button class="btn btn-sm" v-if="current_user_id != null">Actualizar</button>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
    import VModal from 'vue-js-modal';
    Vue.use(VModal);

    const MODAL_WIDTH = 720;
    export default {
        props: {
            closeImg: {
                type: String,
                default: ""
            },
            url: {
                type: String,
                default: ""
            },
            urlDetail: {
                type: String,
                default: ""
            },
        },
        data() {
            return {
                modalWidth: MODAL_WIDTH,
                current_user_id: null,
                user: {
                    id: null,
                    name: '',
                    email: '',
                    roles_list: []
                }
            };
        },
        created() {
            this.modalWidth = window.innerWidth < MODAL_WIDTH ? MODAL_WIDTH / 2 : MODAL_WIDTH;
            
        },
        mounted() {
            this.$parent.$on('userModal', function(id){
                this.current_user_id = id;
                this.$modal.show('userModal');
            }.bind(this));

            this.$parent.$on('refresh', function() {
                this.$modal.hide('userModal');
            })
        },
        methods: {
            beforeOpen() {
                if(!this.current_user_id) {
                    let current_roles = this.$parent.$store.getters.getRoles;
                    for (let index = 0; index < current_roles.length; index++) {
                        const element = current_roles[index];
                        let checkbox_model = {
                            id: element.id,
                            value: false
                        }
                        this.user.roles_list.push(checkbox_model);
                    }
                    return;
                }
                this.$parent.$emit('loading', true);
                let params = {
                    id: this.current_user_id
                };
                axios.post(this.urlDetail, params).then(response => {
                    this.$parent.$emit('loading', false);
                    this.user = response.data.user;
                }).catch(error => {
                    this.$parent.$emit('loading', false);
                });
            },
            closed() {
                this.current_user_id = null;
                this.user = {
                    id: null,
                    name: '',
                    email: '',
                    roles_list: []
                }
            }
        }
    };
</script>
<style lang="scss">
    .pop-out-enter-active,
    .pop-out-leave-active {
        transition: all 0.5s;
    }
    .pop-out-enter,
    .pop-out-leave-active {
        opacity: 0;
        transform: translateY(50px);
    }
</style>
