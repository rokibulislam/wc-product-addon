<template>
    <div class="panel-field">
        <label class="contactum-label"> {{  label }} </label>
        <el-color-picker
        show-alpha
        @active-change="changeColor"
        v-model="value"
        ></el-color-picker>
    </div>
</template>

<script>

export default {
    name: "ColorPicker",
    props: [ 'label', 'value', 'name'],
    data() {
        return {
            color: '',
            previous_color: ''
        }
    },
    methods: {
        changeColor(color) {
            let obj = {[this.name]: color};
            console.log('color');
            console.log(color);
            this.$emit('updateColor', obj )
        }
    }
}
</script>