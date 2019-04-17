<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        props: ['thread'],

        components: { Replies, SubscribeButton },

        data() {
            return {
                repliesCount: this.thread.replies_count,
                //                repliesCount: this.dataRepliesCount,
                locked: this.thread.locked,
                title: this.thread.title,
                body: this.thread.body,
                form: {},
                editing: false
            }
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {
                let uri = `/thread-lock/${this.thread.slug}`;
                //                axios.post('/thread-lock/' + this.thread.slug);
                axios[this.locked ? 'delete' : 'post'](uri);
                this.locked = !this.locked;
            },

            update() {
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
                axios.patch(uri, this.form)
                     .then(() =>{
                         flash('Your thread has been updated')
                         this.title = this.form.title;
                         this.body = this.form.body;
                         this.editing = false
                     });
            },

            resetForm() {
                this.form = {
                    'title': this.title,
                    'body' : this.body
                };

                this.editing = false
            }
        }
    }
</script>