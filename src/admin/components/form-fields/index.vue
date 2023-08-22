<template>
  <ul class="field-panel panel-header">
    <li class="panel-form-fields" v-for="(section, index) in panel_sections" :key="index">
      <h3  @click="panel_toggle(index)">{{ section.title }}
        <i :class="[section.show ? 'fa fa-angle-down' : 'fa fa-angle-right']"> </i>
      </h3>

        <transition name="slide-fade">
            <ul v-show="section.show" class="panel-form-field-buttons">
                <li
                  v-if="is_failed_to_validate(field)"
                  v-for="(field, index) in section.fields"
                  @click="alert_invalidate_msg(field)"
                  class="panel-button button-faded"
                  :key="index"
                  :data-form-field="field"
                  data-source="panel"
                >
                    <i v-if="field_settings[field].icon" :class="['fa fa-' + field_settings[field].icon]" aria-hidden="true"></i>
                    {{ field_settings[field].title}}
                </li>
                <li
                  v-if="!is_failed_to_validate(field)"
                  v-for="(field, index) in section.fields"
                  @click="add_field(field)"
                  class="panel-button"
                  :key="index"
                  :data-form-field="field"
                  data-source="panel"
                >
                    <i v-if="field_settings[field].icon" :class="['fa fa-' + field_settings[field].icon]" aria-hidden="true"></i>
            {{ field_settings[field].title}}</li>
            </ul>
        </transition>
    </li>
  </ul>
</template>

<script>
import { v4 as uuidv4 } from "uuid";
export default {
  name: "form_fields",
  components: {},
  computed: {
    panel_sections: function() {
      return this.$store.getters.panel_sections;
    },
    field_settings: function() {
      return this.$store.getters.field_settings;
    },
    form_fields: function() {
      return this.$store.getters.form_fields;
    }
  },
  mounted: function() {
    // bind jquery ui draggable
    jQuery(this.$el).find('.panel-form-field-buttons .panel-button').draggable({
        connectToSortable: '.form-preview-stage .contactum-form, .contactum-column-inner-fields .contactum-column-fields-sortable-list',
        helper: 'clone',
        revert: 'invalid',
        cancel: '.button-faded',
    }).disableSelection();
  },
  methods: {
    alert_invalidate_msg(field) {
        let validator = this.field_settings[field].validator;

        if (validator && validator.msg) {
            this.$swal({
                title: validator.msg_title || '',
                html: validator.msg,
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#46b450',
                confirmButtonText: 'OK'
            });
        }
    },

    add_field(field_template) {
      let payload = {
        ...this.field_settings[field_template].field_props,
        id: uuidv4()
      };

      if (!payload.name && payload.label) {
        payload.name = payload.label.replace(/\W/g, "_").toLowerCase();

        var same_template_fields = this.form_fields.filter(form_field => {
          return form_field.template === field_template;
        });

        if (same_template_fields.length) {
          payload.name += "_" + same_template_fields.length;
        }
      }

      this.$store.dispatch("add_field", payload);
    },
    panel_toggle: function (index) {
        this.$store.dispatch('panel_toggle', index);
    },
  }
};
</script>

<style scoped>


.panel-form-fields h3 i {
    float: right;
}

.field-panel li ul {
    padding-left: 10px;
    padding-right: 10px;
}

.panel-form-field-buttons li {
    display: inline-block;
    width: 47%;
    /* width: 125px; */
    margin-right: 10px;
    text-align: center;
    padding: 10px 15px;
    box-sizing: border-box;
    cursor: move;
    color: #000;
    background: #fff;
    font-weight: 500;
    box-shadow: 0 1px 2px 0 #d9d9da;
    margin-bottom: 15px;
    transition: background .5s ease;
    border-radius: 8px;
}

ul.panel-form-field-buttons li:hover{
    /* background: #7e3bd0; */
    background: #409EFF;
    color: #fff;
}

</style>
