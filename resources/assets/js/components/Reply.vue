<template>
    <div>
        <div class="card" :id="'reply-'+id">
            <div class="card-header" :class="isBest?'bg-success text-white':'bg-light'">
                <div class="level">
                    <h6 class="flex">
                        <a href="'/profile/'+reply.owner.name"> {{ reply.owner.name }} </a>
                        said... <span v-text="ago"></span>
                    </h6>
                    <div>
                        <favorite :reply="reply"></favorite>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <article>
                    <div v-if="editing">
                        <div class="form-group">
                            <textarea class="form-control" v-model="body" v-text="body" required></textarea>
                        </div>
                        <button class="btn btn-primary btn-sm mr-1" @click="update">update</button>
                        <button class="btn btn-link btn-sm" @click="editing=false" type="button">cancel</button>
                    </div>
                    <div v-else>
                        <div class="body" v-html="body"></div>
                    </div>
                </article>
                <hr>
            </div>
            <div>
                <div class="card-footer level" v-if="authorize('owns',reply) || authorize('owns',reply.thread)">
                    <div v-if="authorize('owns',reply)">
                        <button class="btn btn-sm btn-default mr-1" @click="editing=true">edit</button>
                        <button class="btn btn-sm btn-danger mr-1" @click="destroy">delete</button>
                    </div>
                    <button class="btn btn-sm btn-default ml-a" @click="markBestReply"
                            v-if="authorize('owns',reply.thread)">
                        best reply?
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import 'jquery.caret';
    import 'at.js';
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                body: this.reply.body,
                id: this.reply.id,
                isBest: this.reply.isBest,
            }
        },

        computed: {
            ago(){
                //                return moment(this.reply.created_at+'Z').fromNow();
                return moment.utc(this.reply.created_at + 'Z').fromNow();
            },
            //            signedIn() {
            //                return window.App.signedIn
            //            } //Vue.prototype.signedIn = window.App.signedIn
        },

        created() {
            window.events.$on('best-reply-selected', id =>{
                this.isBest = (id === this.id);
            });
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    /*
                     If function is given, At.js will invoke it if local filter can not find any data
                     @param query [String] matched query
                     @param callback [Function] callback to render page.
                     */
                    remoteFilter: function(query, callback){
                        //                         console.log('Called');
                        // console.log('query');

                        $.getJSON("/api/users", { name: query }, function(usernames){
                            callback(usernames)
                        });
                    }
                }
            })

        },

        methods: {
            update(){
                axios.patch('/replies/' + this.reply.id, { body: this.body })
                     .catch(error =>{
                         flash(error.response.data, 'danger');
                     });
                this.body = this.reply.body;
                this.editing = false;
                flash('updated');
            },

            destroy() {
                axios.delete('/replies/' + this.reply.id);
                this.$emit('deleted', this.reply.id);
                //                $(this.$el).fadeOut(300, () => flash('deleted'));
            },

            markBestReply() {
                this.isBest = true;
                axios.post('/replies/' + this.reply.id + '/best');
                window.events.$emit('best-reply-selected', this.reply.id);
            }
        }
    }
</script>