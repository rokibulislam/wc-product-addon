<template>
<div>
    <div v-if=" editfield_id == 0" class="options-fileds-section text-center">
        <p>
            <span class="loader"></span>
        </p>
    </div>
      <div class="panel-field" v-else>

        <div name="slide-fade" class="panel-field-group panel-header">
            <h2 @click="show_basic_settings = !show_basic_settings">Basic Options
                <!-- <i :class="[show_basic_settings ? 'fa fa-angle-down' : 'fa fa-angle-right']"> </i> -->
                <i :class="[show_basic_settings ? 'el-icon-arrow-down' : 'el-icon-arrow-up']"> </i>
            </h2>
          <transition name="slide-fade">
            <ul v-show="show_basic_settings">
              <li v-for="(field,index) in basic_settings" :key="index">
                <component :is="'field_' + field.type " :field="field" :editfield="editfield"></component>
              </li>
            </ul>
          </transition>
        </div>
        <div name="slide-fade" class="panel-field-group panel-header">
          <h2 @click="show_advanced_settings = !show_advanced_settings"> Advance Options
            <i :class="[show_basic_settings ? 'el-icon-arrow-down' : 'el-icon-arrow-up']"> </i>
            <!-- <i :class="[show_advanced_settings ? 'fa fa-angle-down' : 'fa fa-angle-right']"> </i>  -->
          </h2>
          <transition name="slide-fade">
            <ul v-show="show_advanced_settings">
              <li v-for="(field,index) in advanced_settings" :key="index">
                <component :is="'field_' + field.type " :field="field" :editfield="editfield"></component>
              </li>
            </ul>
          </transition>
        </div>
      </div>
</div>
</template>

<script>
import field_text from "../field-template/text.vue";
import field_text_meta from "../field-template/text-meta.vue";
import field_textarea from "../field-template/textarea.vue";
import field_select from "../field-template/select.vue";
import field_radio from "../field-template/radio.vue";
import field_required from "../field-template/required.vue";
import field_multiselect from "../field-template/multiselect.vue";
import field_checkbox from "../field-template/checkbox.vue";
import field_option_data from "../field-template/option-data.vue";
import field_conditional_logic from "../field-template/conditional-logic.vue";
import field_html_help_text from "../field-template/html-help-text.vue";

export default {
  name: "field_options",
  components: {
    field_text,
    field_text_meta,
    field_textarea,
    field_select,
    field_radio,
    field_multiselect,
    field_checkbox,
    field_option_data,
    field_conditional_logic,
    field_required,
    field_html_help_text,
  },

  data: function data() {
    return {
        show_basic_settings: true,
        show_advanced_settings: false,
    };
  },
  computed: {
    field_settings: function() {
      return this.$store.getters.field_settings;
    },
    current_panel: function() {
      return this.$store.getters.current_panel;
    },
    editfield_id: function() {
      return this.$store.getters.editfield;
    },
    editfield: function() {
      var self = this;
      // let field = this.$store.getters.form_fields.find((item) => this.editfield_id === item.id )

        var form_fields = this.$store.getters.form_fields;

        for (let i = 0; i < form_fields.length; i++) {
        
          if( this.editfield_id === form_fields[i].id  ) {
            return form_fields[i]
          }
        
          if (form_fields[i].template === 'column_field') {
                var innerColumnFields = form_fields[i].inner_fields;
                for (const columnFields in innerColumnFields) {
                    if (innerColumnFields.hasOwnProperty(columnFields)) {
                        var columnFieldIndex = 0;
                        while (columnFieldIndex < innerColumnFields[columnFields].length) {
                            if (innerColumnFields[columnFields][columnFieldIndex].id === self.editfield_id) {
                                return innerColumnFields[columnFields][columnFieldIndex];
                            }
                            columnFieldIndex++;
                        }
                    }
                }
          }

        }
      // return field[0];
    },
    editfieldsetiing: function() {
        return this.field_settings[this.editfield.template].settings.sort((a,b) =>  a.priority - b.priority );
    },

    basic_settings: function() {
      return this.editfieldsetiing.filter( item => item.section == "basic" );
    },

    advanced_settings: function() {
      return this.editfieldsetiing.filter( item => item.section == "advanced" );
    }
  },
  methods: {
    close: function(e) {
      this.$store.dispatch("close_field_options", this.field);
    }
  }
};
</script>

<style lang="scss">

.panel-field {
    margin-bottom: 10px;
    position: relative;
    ul {
        padding-left: 10px;
        padding-right: 10px;
    }
}

.panel-field-group h2 i {
    float: right;
}
</style>
