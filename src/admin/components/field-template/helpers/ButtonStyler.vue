<template>
    <field_color v-model="bgcolor"> </field_color>
</template>


<script>
import field_color from './ColorPicker.vue';
export default {
    name: 'ButtonStyler',
    components: {
        field_color
    },
    data () {
        return {
            bgcolor: "",
        }
    }
}

</script>