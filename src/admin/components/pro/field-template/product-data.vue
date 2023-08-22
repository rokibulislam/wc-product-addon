 <template>
  <div>
    <div>
      <label class="contactum-label">Product List Type</label>

      <el-radio-group v-model="select_type" class="ml-4">
        <el-radio label="radio" size="large">Radio</el-radio>
        <el-radio label="checkbox" size="large">Checkbox</el-radio>
      </el-radio-group>
      <!-- <ul>
        <li>
          <label>
            <input type="radio" value="radio" v-model="select_type" /> Radio
          </label>
        </li>
        <li>
          <label>
            <input type="radio" value="checkbox" v-model="select_type" /> Checkbox
          </label>
        </li>
      </ul> -->
    </div>

    <div>
      <label class="contactum-label">{{ field.title }}</label>
      <ul style="display: flex;
    justify-content: space-around;">
        <li>{{ 'Name' }}</li>
        <li>{{ 'Price' }}</li>
        <li></li>
      </ul>

      <ul>
        <li v-for="(option, index) in options" :key="index" style="display: flex;">
          <div class="selector">
            <template v-if="select_type == 'radio'">
              <input type="radio" :value="option.type" v-model="selected" />
            </template>

            <template v-else>
              <input type="checkbox" :value="option.type" v-model="selected" />
            </template>
          </div>

          <div>
            <el-input v-model="option.name" />
            <!-- <input type="text" v-model="option.name" /> -->
          </div>

          <div>
            <el-input-number v-model="option.price"  />
            <!-- <input type="number" v-model="option.price" /> -->
          </div>

          <div>
            <!-- <button class="fa fa-minus-circle" @click="delete_option(index)"> </button> -->
            <el-button type="danger" @click="delete_option(index)"> 
              <i class="fa fa-minus-circle"> </i>
            </el-button>
              <!-- <span class="dashicons-trash"></span> -->
          </div>
        </li>
      </ul>

      <div>
        <!-- <a href @click.prevent="add_option" class="add-option">{{ 'Add Option' }}</a> -->
        <el-button type="primary" @click.prevent="add_option" class="add-option"> {{ 'Add Option' }}  </el-button>
        <el-button  type="danger" @click.prevent="clear_selection">{{ 'Clear Selection' }} </el-button>
      </div>
      <!-- <a href="#" @click.prevent="clear_selection">{{ 'Clear Selection' }}</a> -->
    </div>
  </div>
</template>

<script>
import option_field from "../../../mixin/option-field.js";
export default {
  name: "field_product_data",
  mixins: [option_field],
  props: {
    field: {
      type: Object,
      default: {}
    }
  },
  data: function() {
    return {
      options: [],
      select_type: "radio",
      selected: "",
      show_value: true
    };
  },
  computed: {
    field_options: function() {
      return this.editfield.options;
    },
    field_selected: function() {
      return this.editfield.selected;
    },
    field_select_type: function() {
      return this.editfield.select_type;
    }
  },
  mounted: function() {
    this.set_options();
  },
  methods: {
    set_options: function() {
      let f_options = { ...this.editfield.options };
      for (const [price, name] of Object.entries(f_options)) {
        this.options.push({
          price: price,
          name: name
        });
      }
      this.selected = this.field_selected;
      this.select_type = this.field_select_type;
    },
    clear_selection: function() {
      this.selected = null;
    },
    add_option: function() {
      let name = "Product-" + this.options.length;
      this.options.push({
        price: 10,
        name: name
      });
    },
    delete_option: function(index) {
      this.options.splice(index, 1);
    }
  },
  watch: {
    options: {
      deep: true,
      handler: function(new_option) {
        let options = {},
          i = 0;
        for (i = 0; i < new_option.length; i++) {
          options[new_option[i].name] = new_option[i].price;
        }

        this.$store.dispatch("update_editing_form_field", {
          id: this.editfield.id,
          property: "options",
          value: options
        });
      }
    },
    select_type: function(new_value) {
      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: "select_type",
        value: new_value
      });
    },
    selected: function(new_value) {
      this.$store.dispatch("update_editing_form_field", {
        id: this.editfield.id,
        property: "selected",
        value: new_val
      });
    }
  }
};
</script>
