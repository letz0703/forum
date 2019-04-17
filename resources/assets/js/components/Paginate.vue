<template>
    <div>
        <ul class="pagination" v-if="shouldPaginate">
            <li class="page-item" v-show="prevUrl">
                <a class="page-link" href="#" aria-label="Previous" @click="page--">
                    <span aria-hidden="true">&laquo; previous</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <!--<li class="page-item"><a class="page-link" href="#">1</a></li>-->
            <!--<li class="page-item"><a class="page-link" href="#">2</a></li>-->
            <!--<li class="page-item"><a class="page-link" href="#">3</a></li>-->
            <li class="page-item" v-show="nextUrl">
                <a class="page-link" href="#" aria-label="Next" @click="page++">
                    <span aria-hidden="true">next &raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['dataSet'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,
            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            page() {
                this.broadcast().updateUrl();
            }

        },

        computed: {
            shouldPaginate(){
                return !! this.prevUrl || !! this.nextUrl ;
            }
        },

        methods: {
            broadcast() {
                this.$emit('updated',this.page);
                return this;
            },
            updateUrl() {
                history.pushState(null,null,'?page='+this.page);
            }
        }
    }
</script>