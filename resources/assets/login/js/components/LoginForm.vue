<template>
    <form @submit.prevent="$parent.formController(url,$event)">
        <div class="form-group">
            <input 
                class="form-control"
                type="text"
                placeholder="Correo"
                name="email"
                id="email"
                autocomplete="off"
                v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
            >
            <div class="invalid-feedback email-error"></div>
        </div>
        <div class="form-group">
            <input 
                class="form-control" 
                type="password" 
                placeholder="Clave" 
                name="password" 
                id="password" 
                v-on="{ click: $parent.clearErrorMsg, focusin: $parent.clearErrorMsg }"
            >
            <div class="invalid-feedback password-error"></div>
        </div>
        <div class="form-group">
            <button class="btn btn-info">
                Ingresar
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            url : ""
        },
        data(){
            return {
            }
        },
        computed: {
        },
        created() {
        },
        mounted() {
            $('#email').focus();
        },
        methods: {
        },
        directives: {
        },
    }
</script>
