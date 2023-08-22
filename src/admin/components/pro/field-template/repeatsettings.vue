<template>
    <div class="panel-field">
        
        <label class="contactum-label"> Repeat Field Settings </label>

        <div class="repeat-field-option__settings">
            
            <div class="panel-field">
                <label class="contactum-label"> Field Type </label>
                <el-select v-model="fields">
                    <el-option v-for="(element,elementName) in available_elements" :key="elementName" :value="elementName" :label="element"></el-option>
                </el-select>
            </div>

            <div class="panel-field">
                <label class="contactum-label"> Label </label>
                <el-input v-model="editfield.field_properties.title"> </el-input>
            </div>

            <div class="panel-field">
                <label class="contactum-label"> Placeholder </label>
                <el-input v-model="editfield.field_properties.placeholder"> </el-input>
            </div>

            <div v-if="fields == 'form_dropdown_field'" class="panel-field">
                <label class="contactum-label"> Options </label>
                <field_option_data :editfield="editfield.field_properties" :field="editfield" />
            </div>
            
            <div v-if="fields == 'form_text_field' || fields == 'form_number_field' || fields == 'form_email_address' " class="panel-field">
                <label class="contactum-label"> Default </label>
                <el-input v-model="editfield.field_properties.default"> </el-input>
            </div>

            <div class="panel-field">
                <label class="contactum-label"> Required </label>
                <ul class="list-inline">
                    <li>
                        <label>
                            <el-radio :label="true" v-model="editfield.field_properties.required"> True </el-radio>
                            <el-radio :label="false" v-model="editfield.field_properties.required"> false  </el-radio>
                        </label>
                    </li>
                </ul>
            </div>

            <div v-if=" editfield.field_properties.required == true " class="panel-field"> 
                <label class="contactum-label"> Error Message: </label>
                <el-input v-model="editfield.field_properties.message"></el-input>
            </div>

        </div>

    </div>

</template>

<script>

import option_field from "../../../mixin/option-field.js";
import field_option_data from '../../field-template/option-data.vue'
import field_required from '../../field-template/required.vue'

export default {
    name: "field_repeatsettings",
    mixins: [option_field],
    components: {
        field_option_data,
        field_required
    },
    data: function() {
        return {
            available_elements: {
                'form_text_field': 'Text Field',
                'form_email_address': 'Email Field',
                'form_number_field': 'Numeric Field',
                'form_dropdown_field': 'Select Field'
            }
        }
    },
    computed: {
        fields: {
            get: function() {
                return this.editfield['fields'];
            },
            set: function(value) {
                console.log(value);
                this.$store.dispatch("update_editing_form_field", {
                    id: this.editfield.id,
                    property: 'fields',
                    value: value
                });
            }
        }
    },
}

</script>