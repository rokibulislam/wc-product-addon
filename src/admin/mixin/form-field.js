export default {
    props: {
        field: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    methods: {
        class_names: function(custom_class) {
            return [
                custom_class,
                this.required_class(),
                this.field.name
            ];
        },

        required_class: function() {
            return this.field.required === "yes" ? "required" : "";
        },

        is_selected(value) {
          if (Array.isArray(this.field.selected)) {
            return this.field.selected.includes(value);
          } else if (value == this.field.selected) {
            return true;
          }

          return false;
        }
    }
};
