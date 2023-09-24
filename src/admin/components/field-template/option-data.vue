<template>
  <div>
    <div>
      <label class="wcprafe-label">{{ field.name }}</label>
      <ul class="field-option-actions">
        <li>
          <el-checkbox v-model="editfield.show_value" :label="show_value"> {{ "Show values" }} </el-checkbox>
        </li>
        <li>
          <el-checkbox v-model="editfield.sync_value" :label="sync_value"> {{ "Sync values" }} </el-checkbox>
        </li>
        <!-- <li v-if="editfield.image">
            <el-checkbox v-model="editfield.photo_value" :label="photo_value"> {{ "Photo" }} </el-checkbox>
        </li> -->
      </ul>
    </div>

    <ul :class="['field-options', 'option-field-swapper']">
      <li
        v-for="(option, index) in options"
        :key="index"
        class="option-field-option"
      >
        <div class="selector">
          <input
            v-if="field.is_multiple"
            type="checkbox"
            :value="option.value"
            v-model="selected"
          />
          <input
            v-else
            type="radio"
            :value="option.value"
            v-model="selected"
            class="option-chooser-radio"
          />
        </div>

        <div class="sort-handler">
          <i class="fa fa-bars"></i>
        </div>
        
        <!-- <div v-if="editfield.photo_value == true ">
          <div class="ff_photo_card">
            <div class="wpf_photo_holder">
              <img
                style="max-width: 100%"
                v-if="option.photo"
                :src="option.photo"
                width="50px"
              />
              <div class="photo_widget_btn">
                <i class="el-icon-upload" @click="initUploader(option)"> </i>
                <i class="el-icon-delete" v-if="option.photo" @click="option.photo = ''"> </i>
              </div>
            </div>
          </div>
        </div> -->

        <div class="option_label">
          <el-input type="text" v-model="option.label" @input="set_option_value(index, option.label)"> </el-input>
        </div>

        <div v-if="editfield.show_value" class="option_value">
          <el-input type="text" v-model="option.value"> </el-input>
          <!-- <input type="text" v-model="option.value" /> -->
        </div>
        <!-- <el-button type="danger" v-if="options.length > 1" @click="delete_option(index)"> -->
          <!-- <i class="fa fa-minus-circle"  v-if="options.length > 1" @click="delete_option(index)"></i> -->
          <i class="el-icon-remove-outline"  v-if="options.length > 1" @click="delete_option(index)"></i>
        <!-- </el-button> -->
      </li>
    </ul>
    <div class="option-actions">
      <!-- <button href @click.prevent="add_option" class="option-add option-btn"> -->
      <!-- <button type="primary" @click.prevent="add_option"> -->
        <!-- <i class="fa fa-plus-circle" @click.prevent="add_option"></i> -->
        <i class="el-icon-circle-plus-outline" @click.prevent="add_option"></i>
      <!-- </button> -->
      <!-- <button @click="initBulkEdit()" class="option-btn  bulkedit"> Bulk Edit </button> -->
     <!-- {{  selected }} -->
     <!-- {{  field.is_multiple }} -->
     <!-- class="clearselection option-remove option-btn" -->
      <el-button
        type="danger"
        v-if="!field.is_multiple && selected.length > 0"
        @click.prevent="clear_selection"
      >
        {{ "Clear Selection" }}
      </el-button>
    </div>

    <!-- use the modal component, pass in the prop -->
    <modal v-if="bulkEditVisible" @close="bulkEditVisible = false">
      <div slot="body">
        <textarea v-model="value_key_pair_text">
 {{ value_key_pair_text }} </textarea
        >
      </div>
      <span slot="footer" class="dialog-footer">
        <button @click="bulkEditVisible = false">Cancel</button>
        <button type="primary" @click="confirmBulkEdit()">Confirm</button>
      </span>
    </modal>
  </div>
</template>

<script>
import option_field from "../../mixin/option-field.js";
import modal from "../modal/index.vue";
export default {
  name: "field_option_data",
  mixins: [option_field],
  components: {
    modal,
  },
  data: function () {
    return {
      options: [],
      selected: [],
      bulkEditVisible: false,
      value_key_pair_text: "",
    };
  },
  computed: {
    field_selected: function () {
      return this.editfield.selected;
    },
  },
  mounted: function () {
    if (!window.wpActiveEditor) {
      window.wpActiveEditor = null;
    }
    var self = this;

    this.set_options();

    jQuery(this.$el)
      .find(".option-field-swapper")
      .sortable({
        items: ".option-field-option",
        handle: ".sort-handler",
        update: function (e, ui) {
          var item = ui.item[0],
            data = item.dataset,
            toIndex = parseInt(jQuery(ui.item).index()),
            fromIndex = parseInt(data.index);

          self.options.swap(fromIndex, toIndex);
        },
      });
  },
  methods: {
    initBulkEdit: function () {
      let astext = "";

      this.options.map((item, index) => {
        astext += item.label;
        astext += ":" + item.value;
        astext += String.fromCharCode(13, 10);
      });

      this.value_key_pair_text = astext;

      this.bulkEditVisible = true;
    },

    confirmBulkEdit() {
      let lines = this.value_key_pair_text.split("\n");
      let values = [];

      lines.filter((line) => {
        let lineItem = line.split(":");
        let label = lineItem[0];
        let value = lineItem[1];
        if (!value) {
          value = label;
        }
        if (label && value) {
          values.push({
            label: label,
            value: value,
          });
        }
      });

      this.options = values;
      this.bulkEditVisible = false;
    },

    set_options: function () {
      let f_options = { ...this.editfield.options };
      /*
      for (const [label, value] of Object.entries(f_options)) {
        this.options.push({
          label: label,
          value: value,
          photo: "",
        });
      }
      */
      this.options = this.editfield.options;

      if (this.editfield.is_multiple && !Array.isArray(this.field_selected)) {
        this.selected = [this.field_selected];
      } else {
        this.selected = this.field_selected;
      }
    },
    clear_selection: function () {
      this.selected = [];
      // this.field_selected.selected = null;
    },
    add_option: function () {
      let length = this.options.length + 1;
      let label = `Option-${length}`;
      let value = `option-${length}`;
      this.options.push({
        label: label,
        value: value,
        photo: "",
      });
      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: "options",
        value: this.options,
      });
    },
    delete_option: function (index) {
      this.options.splice(index, 1);

      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: this.field.name,
        value: this.options,
      });
    },

    set_option_value: function (index, label) {
      if (this.sync_value) {
        this.options[index].value = label
          .toLocaleLowerCase()
          .replace(/\s/g, "_");
      }
    },

    isLodash: function() {
        let isLodash = false;

        // If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
        if ( 'undefined' != typeof( _ ) && 'function' == typeof( _.forEach ) ) {

            // A small sample of some of the functions that exist in lodash but not underscore
            const funcs = [ 'get', 'set', 'at', 'cloneDeep' ];

            // Simplest if assume exists to start
            isLodash  = true;

            funcs.forEach( function ( func ) {
                // If just one of the functions do not exist, then not lodash
                isLodash = ( 'function' != typeof( _[ func ] ) ) ? false : isLodash;
            } );
        }

        if ( isLodash ) {
            // We know that lodash is loaded in the _ variable
            return true;
        } else {
            // We know that lodash is NOT loaded
            return false;
        }
    },

    initUploader(option) {
      console.log("option value", option);
      console.log('hello');
      // e.preventDefault();
      
      if ( this.isLodash() ) {
        _.noConflict();
      }

      console.log( wp.media );

      if (typeof wp !== "undefined" && wp.media  ) {
        var self = this;
        var send_attachment_bkp = wp.media.editor.send.attachment;
        wp.media.editor.send.attachment = function (props, attachment) {
          option.photo = attachment.url;
          wp.media.editor.send.attachment = send_attachment_bkp;
        };
        wp.media.editor.open();
      }
      return false;
    },
  },

  watch: {
    options: {
      deep: true,
      handler: function (new_option) {
        console.log("new option");
        console.log(new_option);
        /*
        let options = {};
        new_option.map((item, index) => {
          options[item.value] = item.label;
        });
        */
        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: this.field.name,
          // value: options,
          value: new_option,
        });
      },
    },

    selected: function (new_val) {
      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: "selected",
        value: new_val,
      });
    },
  },
};
</script>

<style type="text/css" scoped>
.field-option-actions {
  display: flex;
  justify-content: space-between;
}

.option-field-option i {
  align-items: center;
}

.field-options {
  margin-bottom: 5px;
}
.field-options li {
  display: flex;
  align-items: center;
}
.field-options li > div.selector {
  margin-right: 5px;
}

.field-options li > div.option_label,
.field-options li > div.option_value {
  margin-right: 10px;
}
.field-options {
  margin: 10px 0px;
}

.field-options button {
  /* width: 30px;
  border: none;
  background: red;
  color: #fff;
  height: 28px;
  margin-top: 5px; */
}

.bulkedit {
  background: green;
}

.clearselection {
  background: red;
}

.option-field-swapper .sort-handler {
  width: 22px;
  cursor: ns-resize;
}

.photo_widget_btn i {
  font-size: 20px;
}

 /* .ff_photo_card {
  position: relative;
}

.photo_widget_btn {
  position: absolute;
  top: 0;
  bottom: 0;
}
.photo_widget_btn_clear {
  position: absolute;
  top: 0;
  right: 0;
  color: red;
}  */
</style>
