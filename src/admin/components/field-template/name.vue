<template>
  <div class="panel-field panel-field-name">
    <div class="panel-field-option">
        <el-checkbox v-model="editfield.first_name.show_field"> First Name </el-checkbox>
        <i class="fa fa-caret-down panel-right" @click="toggleNameFieldInputs"></i>

        <div class="name-option__settings">
          <div class="wcprafe-label">
            <label class="wcprafe-label">{{ 'First Name Label' }}</label>
            <el-input v-model="editfield.first_name.label"/>
          </div>

          <div class="panel-field-first_name wcprafe-label">
            <label class="wcprafe-label">{{ 'First Name Placeholder' }}</label>
            <el-input v-model="editfield.first_name.placeholder" />
          </div>
          
          <div class="wcprafe-label">
            <label class="wcprafe-label">{{ 'First Name Default Value' }}</label>
            <el-input v-model="editfield.first_name.default" />
          </div>

          <div class="wcprafe-label">
            <label class="wcprafe-label">{{ 'Required' }}</label>
            <el-radio-group v-model="editfield.first_name.required">
              <el-radio :label="true" > Yes </el-radio> 
              <el-radio :label="false"> No </el-radio>
            </el-radio-group>
          </div>

          <div v-if=" editfield.first_name.required == true "> 
            <label class="wcprafe-label"> Error Message: </label>
            <el-input v-model="editfield.first_name.message"></el-input>
          </div>

        </div>
    </div>

    <div class="panel-field-option">
        <el-checkbox v-model="editfield.middle_name.show_field"> Middle Name </el-checkbox>
        <i class="fa fa-caret-down panel-right" @click="toggleNameFieldInputs"></i>

        <div class="name-option__settings">

            <div class="wcprafe-label">
                <label class="wcprafe-label">{{ 'Middle Name Label' }}</label>
                <el-input v-model="editfield.middle_name.label"/>
            </div>

            <div class="panel-field-middle_name wcprafe-label">
                <label class="wcprafe-label">{{ 'Middle Name Placeholder' }}</label>
                <el-input v-model="editfield.middle_name.placeholder"/>
            </div>

            <div class="wcprafe-label">
              <label class="wcprafe-label">{{ 'Middle Name Default Value' }}</label>
              <el-input v-model="editfield.middle_name.default"/>
            </div>
        
          <div class="wcprafe-label">
            <label class="wcprafe-label">{{ 'Required' }}</label>
            <el-radio-group v-model="editfield.middle_name.required">
              <el-radio :label="true" > Yes </el-radio> 
              <el-radio :label="false"> No </el-radio>
            </el-radio-group>
          </div>

          <div v-if=" editfield.middle_name.required == true "> 
            <label class="wcprafe-label"> Error Message: </label>
            <el-input v-model="editfield.middle_name.message"></el-input>
          </div>

        </div>

    </div>

    <div class="panel-field-option">
      <el-checkbox v-model="editfield.last_name.show_field"> Last Name </el-checkbox> 
      <i class="fa fa-caret-down panel-right" @click="toggleNameFieldInputs"></i>

          <div class="name-option__settings">

              <div class="wcprafe-label">
                  <label class="wcprafe-label">{{ 'Last Name Label' }}</label>
                  <el-input v-model="editfield.last_name.label"/>
              </div>

              <div class="panel-field-last_name wcprafe-label">
                  <label class="wcprafe-label">{{ 'Last Name Placeholder' }}</label>
                  <el-input type="text" v-model="editfield.last_name.placeholder"/>
              </div>

              <div class="wcprafe-label">
                <label class="wcprafe-label">{{ 'Last Name Default Value' }}</label>
                <!-- <input type="text" v-model="editfield.last_name.default"/> -->
                <el-input type="text" v-model="editfield.last_name.default"/>
                <!-- <merge_tags filter="no_fields" @insert="insertValue" :field="{name: 'last_name', type: 'default'}" /> -->
              </div>

              <div class="wcprafe-label">
                <label class="wcprafe-label">{{ 'Required' }}</label>
                <el-radio-group v-model="editfield.last_name.required">
                  <el-radio :label="true" > Yes </el-radio> 
                  <el-radio :label="false"> No </el-radio>
                </el-radio-group>
              </div>

              <div v-if=" editfield.last_name.required == true "> 
                <label class="wcprafe-label"> Error Message: </label>
                <el-input v-model="editfield.last_name.message"></el-input>
              </div>
  
          </div>
    </div>

  </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
import merge_tags from "../merge-tags/index.vue";
export default {
  name: "field_name",
  mixins: [option_field],
    components: {
    merge_tags
  },
  date() {
    return {

    }
  },
  computed: {
    value: {
      get: function() {
        let property = this.field.name;
        return this.editfield[property];
      },
      set: function(value) {
        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: this.field.name,
          value: value
        });
      }
    }
  },
  methods: {
    toggleNameFieldInputs: function(event) {
      
      if (! jQuery(event.target).parent().find('.name-option__settings').hasClass('is-open')) {
          jQuery(event.target).removeClass('fa-caret-bottom');
          jQuery(event.target).addClass('fa-caret-up');
          jQuery(event.target).parent().find('.name-option__settings').addClass('is-open');
          // jQuery(event.target).parent().find('.required-checkbox').addClass('is-open');
      } else {
          jQuery(event.target).removeClass('fa-caret-up');
          jQuery(event.target).addClass('fa-caret-bottom');
          jQuery(event.target).parent().find('.name-option__settings').removeClass('is-open');
          // jQuery(event.target).parent().find('.required-checkbox').removeClass('is-open');
      }
      console.log(event.target)
    },
    onfocusout: function(e) {
      this.$eventHub.$emit("field-text-focusout", this.field, this.value);
    },
    onkeyup: function(e) {
      this.$eventHub.$emit("field-text-keyup", this.field, this.value);
    },
    insertValue: function(type, field, property) {
        var value = ( field !== undefined ) ? '{' + type + ':' + field + '}' : '{' + type + '}';
        this.editfield[property.name][property.type] = value;
    },
  }
};
</script>

<style type="text/css" scoped>
    .panel-field-option {
        position: relative;
        margin-right: 5px;
        margin-bottom: 10px;
    }
    .panel-field-last_name, .panel-field-first_name,.panel-field-middle_name {
        position: relative;
    }

    .panel-field-first, .panel-field-last , .panel-field-middle {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .is-open {
      padding: 15px;
      background: #f2f2f2;
      border-radius: 6px;
    }

    .name-option__settings {
      margin-top: 10px;
      display: none;
    }

    .name-option__settings.is-open {
      display: block;
    }

    .panel-right {
      float: right;
    }
</style>
