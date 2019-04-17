export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        add(item){
            this.items.push(item)
            flash('added');
            this.$emit('added');
        },
        remove(index){
            this.items.splice(index,1);
            flash('deleted');
            this.$emit('deleted')
        }
    }
}