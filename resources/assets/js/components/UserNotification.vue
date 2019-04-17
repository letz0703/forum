<template>
    <div>
        <li class="nav-item dropdown" v-if="notifications.length">
            <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre>
                <span class="fa fa-bell"></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <div v-for="notification in notifications">
                    <a class="dropdown-item" :href="notification.data.link" v-text="notification.data.message"
                       @click="markAsRead(notification)"
                       v-show="!notification.read_at"
                    ></a>
                </div>
            </div>
        </li>

    </div>
</template>

<script>
    export default {
        props: [ '' ],

        data() {
            return {
                notifications: false,
                endpoint: "/profiles/" + window.App.user.name + "/notifications/"
            }
        },

        created() {
            axios.get( this.endpoint )
                 .then( response => this.notifications = response.data );
        },

        methods: {
            markAsRead( notification ){
                axios.delete( this.endpoint + notification.id );
            }
        }
    }
</script>