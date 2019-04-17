<template>
    <div>
        <input type="file" accept="image/*" @change="onChange">
    </div>
</template>

<script>
    export default {
        props: [''],

        data() {
            return {}
        },
        methods: {
            onChange(e){
                //                console.log(e);
                if (!e.target.files.length) return;
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e =>{
                    let src = e.target.result;
                    this.$emit('loaded', { src, file });
                }
            },
        }
    }
</script>