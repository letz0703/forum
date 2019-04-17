<template>
    <div>
        <button :class="classes" @click="subscribe">Subscribe</button>
    </div>
</template>

<script>
    export default {
        props: ['active'],

        data() {
            return {
                registered: this.active
            }
        },

        computed: {
            classes() {
                return ['btn-sm',this.registered?'':'btn-danger']
            }
        },

        methods: {
            subscribe() {
                axios[(this.registered? 'delete': 'post')](location.pathname+'/subscriptions');
                this.registered = !this.registered ;
                flash('subscribed');
            }
        }
    }
</script>