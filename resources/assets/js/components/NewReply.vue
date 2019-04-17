<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <!--<wysiwyg name="body" v-model="body"-->
                         <!--placeholder="Have something to say"-->
                <!--&gt;</wysiwyg>-->
            <textarea v-model="body"
                      name="body"
                      id="body"
                      class="form-control"
                      placeholder="Have something to Say?"
                      required
                      rows="5"></textarea>
            </div>
            <button type="submit" class="btn btn-default" @click="addReply">Post</button>
        </div>
        <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate in this discussion</p>
    </div>
</template>

<script>
    export default {
        props: [''],

        data() {
            return {
                'body': '',
                'endpoint': location.pathname + '/replies'
            }
        },

        methods: {
            addReply(){
                axios.post(this.endpoint, {body: this.body})
                     .catch(error => {
                         flash(error.response.data, 'danger');
                     })
                    .then(({data}) => {
                        this.body = '';
                        this.$emit('added', data);
                        flash('new reply has been added');
                    });
            }
        },
    }
</script>