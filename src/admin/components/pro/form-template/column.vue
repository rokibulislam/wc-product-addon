<template>
<div v-bind:class="['contactum-field-columns', 'has-columns-'+field.columns]">
    <div class="contactum-column-field-inner-columns">
        <div class="contactum-column">
            <!-- don't change column class names -->
            <div v-for="column in columnClasses" :class="[column, 'items-of-column-'+field.columns, 'contactum-column-inner-fields']" :style="{ width: field.inner_columns_size[column], paddingRight: field.column_space+'px'}">
                <ul class="contactum-column-fields-sortable-list">
                    <li
                        v-for="(field, index) in column_fields[column]"
                        :key="field.id"
                        :class="[
                            'column-field-items', 'contactum-el', field.name, field.css, 'form-field-' + field.template,
                            field.width ? 'field-size-' + field.width : '',
                            parseInt(editing_form_id) === parseInt(field.id) ? 'current-editing' : ''
                        ]"
                        :column-field-index="index"
                        :in-column="column"
                        data-source="column-field-stage"
                    >
                        <div v-if="!is_full_width(field.template)" class="contactum-label contactum-column-field-label">
                            <label v-if="!is_invisible(field)" :for="'contactum-' + field.name ? field.name : 'cls'">
                                {{ field.label }} <span v-if="field.required && 'yes' === field.required" class="required">*</span>
                            </label>
                        </div>

                        <!-- <component v-if="is_template_available(field)" :is="'form_' + field.template" :field="field"></component> -->
                        <component :is="'form_' + field.template" :field="field"></component>

                        <!-- <div v-if="is_pro_feature(field.template)" class="stage-pro-alert">
                            <label class="contactum-pro-text-alert">
                                <a :href="pro_link" target="_blank"><strong>{{ get_field_name(field.template) }}</strong> <?php _e( 'is available in Pro Version', 'wp-user-frontend' ); ?></a>
                            </label>
                        </div> -->

                        <div class="contactum-column-field-control-buttons">
                            <p>
                                <i class="fa fa-arrows move"></i>
                                <i class="fa fa-pencil" @click="open_column_field_settings(field, index, column)"></i>
                                <i class="fa fa-clone" @click="clone_column_field(field, index, column)"></i>
                                <i class="fa fa-trash-o" @click="delete_column_field(index, column)"></i>
                            </p>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
</template>


<script>

import form_field from "../../../mixin/form-field.js";
import form_text_field from "../../form-templates/text.vue";

export default {
    name: "form_column_field",
    mixins: [form_field],
    components: { 
        form_text_field 
    },
    data() {
        return{
            columnClasses: ['column-1', 'column-2', 'column-3'] // don't edit class names
        };
    },

    mounted() {
        // this.resizeColumns(this.field.columns);

        // bind jquery ui draggable
        var self = this,
            sortableFields = jQuery(self.$el).find('.contactum-column-inner-fields .contactum-column-fields-sortable-list'),
            sortableTriggered = 1,
            columnFieldArea = jQuery('.contactum-field-columns'),
            columnFields = jQuery(self.$el).find(".contactum-column-field-inner-columns .contactum-column-inner-fields");

        // columnFieldArea.mouseenter(function() {
        //     self.resizeColumns(self.field.columns);
        // });


        columnFieldArea.mouseleave(function() {
            columnFields.unbind( "mouseup" );
            columnFields.unbind( "mousemove" );
        });

        // bind jquery ui sortable
        jQuery(sortableFields).sortable({
            placeholder: 'form-preview-stage-dropzone',
            connectWith: sortableFields,
            items: '.column-field-items',
            handle: '.contactum-column-field-control-buttons .move',
            scroll: true,
            stop: function( event, ui ) {
                console.log('column field stop');
                let    data    = ui.item[0].dataset;
                let    source  = ui.item[0].dataset.source;

                if ('panel' === source) {
                    console.log('mojas');
                    console.log(ui.item[0].parentElement.parentElement);
                    var field_template = ui.item[0].dataset.formField;
                    var class_list = ui.item[0].parentElement.parentElement.classList[0];
                   
                    var payload = {
                        toIndex: parseInt(jQuery(ui.item).index()),
                        field_template: field_template,
                        to_column: class_list
                    };

                    self.add_column_inner_field(payload);

                    // remove button from stage
                    jQuery(this).find('.panel-button.ui-draggable.ui-draggable-handle').remove();
                }

                // var data_source = ui.item.context.attributes['data-source'].value;
                // if ('panel' === data_source) {
                //     var field_template = ui.item[0].dataset.formField;
                //     var payload = {
                //         toIndex: parseInt($(ui.item).index()),
                //         field_template: ui.item.context.attributes['data-form-field'].value,
                //         to_column: jQuery(this).context.parentElement.classList[0]
                //     };

                //     self.add_column_inner_field(payload);

                //     // remove button from stage
                //     // jQuery(this).find('.button.ui-draggable.ui-draggable-handle').remove();
                // }
            },
            update: function (e, ui) {
                console.log('column field update');
                var item    = ui.item[0],
                    data    = item.dataset,
                    source  = data.source,
                    toIndex = parseInt(jQuery(ui.item).index()),
                    payload = {
                        toIndex: toIndex
                    };

                if ( 'column-field-stage' === source) {
                    // payload.field_id   = self.field.id;
                    // payload.fromIndex  = parseInt(ui.item.context.attributes['column-field-index'].value);
                    // payload.fromColumn = ui.item.context.attributes['in-column'].value;
                    // payload.toColumn   = ui.item.context.parentElement.parentElement.classList[0];

                    payload.field_id   = self.field.id;
                    payload.fromIndex  = parseInt(ui.item[0].attributes['column-field-index'].value);
                    payload.fromColumn = ui.item[0].attributes['in-column'].value;
                    payload.toColumn   = ui.item[0].parentElement.parentElement.classList[0];

                    // when drag field one column to another column, sortable event trigger twice and try to swap field twice.
                    // So the following conditions are needed to check and run swap_column_field_elements commit only once
                    if (payload.fromColumn !== payload.toColumn && sortableTriggered === 1) {
                        sortableTriggered = 0;
                    }else{
                        sortableTriggered++;
                    }

                    if (payload.fromColumn === payload.toColumn) {
                        sortableTriggered = 1;
                    }

                    if (sortableTriggered === 1) {
                        self.$store.commit('swap_column_field_elements', payload);
                    }
                }
            }
        });
    },

    computed: {
        column_fields: function () {
            return this.field.inner_fields;
        },

        innerColumns() {
            return this.field.columns;
        },

        editing_form_id: function () {
            return this.$store.state.editfield.id;
        },

        field_settings: function () {
            return this.$store.state.field_settings;
        },
    },

    methods: {
        is_template_available: function (field) {
            var template = field.template;

            if (this.field_settings[template]) {
                if (this.is_pro_feature(template)) {
                    return false;
                }

                return true;
            }

            // for example see 'mixin_builder_stage' mixin's 'is_taxonomy_template_available' method
            if (_.isFunction(this['is_' + template + '_template_available'])) {
                return this['is_' + template + '_template_available'].call(this, field);
            }

            return false;
        },

        is_pro_feature: function (template) {
            return (this.field_settings[template] && this.field_settings[template].pro_feature) ? true : false;
        },

        get_field_name: function (template) {
            return this.field_settings[template].title;
        },

        is_full_width: function (template) {
            if (this.field_settings[template] && this.field_settings[template].is_full_width) {
                return true;
            }

            return false;
        },

        is_invisible: function (field) {
            return ( field.recaptcha_type && 'invisible_recaptcha' === field.recaptcha_type ) ? true : false;
        },

        isAllowedInClolumnField: function(field_template) {
            var restrictedFields = ['column_field', 'custom_hidden_field', 'step_start'];

            if ( jQuery.inArray(field_template, restrictedFields) >= 0 ) {
                return true;
            }

            return false;
        },

        add_column_inner_field(data) {
            var payload = {
                toWhichColumnField: this.field.id,
                toWhichColumnFieldMeta: this.field.name,
                toIndex: data.toIndex,
                toWhichColumn: data.to_column
            };

            if (this.isAllowedInClolumnField(data.field_template)) {
                swal({
                    title: "Oops...",
                    text: "You cannot add this field as inner column field"
                });
                return;
            }

            // check if these are already inserted
            if ( this.isSingleInstance( data.field_template ) && this.containsField( data.field_template ) ) {
                swal({
                    title: "Oops...",
                    text: "You already have this field in the form"
                });
                return;
            }

            var field = jQuery.extend(true, {}, this.$store.state.field_settings[data.field_template].field_props),
            form_fields = this.$store.state.form_fields;

            field.id = this.get_random_id();

            if ('yes' === field.is_meta && !field.name && field.label) {
                field.name = field.label.replace(/\W/g, '_').toLowerCase();

                var same_template_fields = form_fields.filter(function (form_field) {
                    return (form_field.template === field.template);
                });

                if (same_template_fields) {
                    field.name += '_' + this.get_random_id();
                }
            }

            payload.field = field;

            // add new form element
            this.$store.commit('add_column_inner_field_element', payload);
        },

        moveFieldsTo(column) {
            var payload = {
                field_id: this.field.id,
                move_to : column,
                inner_fields: this.getInnerFields()
            };

            // clear inner fields & push mergedFields to column-1
            this.$store.commit('move_column_inner_fields', payload);
        },

        getInnerFields() {
            return this.field.inner_fields;
        },

        open_column_field_settings: function(field, index, column) {
            var self = this,
                payload = {
                    field_id: self.field.id,
                    column_field: field,
                    index: index,
                    column: column,
                };
            self.$store.commit('open_column_field_settings', payload);
        },

        clone_column_field: function(field, index, column) {
            var self = this,
                payload = {
                    field_id: self.field.id,
                    column_field_id: field.id,
                    index: index,
                    toColumn: column,
                    new_id: self.get_random_id()
                };

            // check if the field is allowed to duplicate
            if ( self.isSingleInstance( field.template ) ) {
                swal({
                    title: "Oops...",
                    text: "You already have this field in the form"
                });
                return;
            }

            self.$store.commit('clone_column_field_element', payload);
        },

        delete_column_field: function(index, fromColumn) {
            console.log('delete column');
            var self = this,
                payload = {
                    field_id: self.field.id,
                    index: index,
                    fromColumn: fromColumn
                };

            swal({
                text: "you want to delete this field?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d54e21',
                confirmButtonText: "Yes Delete",
                cancelButtonText: "No Cancel",
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
            }).then(function () {
                self.$store.commit('delete_column_field_element', payload);
            }, function() {

            });
        },

        resizeColumns(columnsNumber) {
            var self = this;

            (function () {
                var columnElement;
                var startOffset;
                var columnField = jQuery(self.$el).context.parentElement;
                var total_width = parseInt($(columnField).width());

                Array.prototype.forEach.call(
                    jQuery(self.$el).find(".contactum-column-field-inner-columns .contactum-column-inner-fields"),

                    function (column) {
                        column.style.position = 'relative';

                        var grip = document.createElement('div');
                        grip.innerHTML = "&nbsp;";
                        grip.style.top = 0;
                        grip.style.right = 0;
                        grip.style.bottom = 0;
                        grip.style.width = '5px';
                        grip.style.position = 'absolute';
                        grip.style.cursor = 'col-resize';
                        grip.addEventListener('mousedown', function (e) {
                            columnElement = column;
                            startOffset = column.offsetWidth - e.pageX;
                        });

                        column.appendChild(grip);
                    });

                jQuery(self.$el).find(".contactum-column-field-inner-columns .contactum-column-inner-fields").mousemove(function( e ) {
                    if (columnElement) {
                    var currentColumnWidth = startOffset + e.pageX;

                    columnElement.style.width = (100*currentColumnWidth) / total_width + '%';
                    }
                });

                jQuery(self.$el).find(".contactum-column-field-inner-columns .contactum-column-inner-fields").mouseup(function() {
                    var columnOneWidth   = $(columnField).find(".column-1").width(),
                        columnTwoWidth   = $(columnField).find(".column-2").width(),
                        colOneWidth      = (100*columnOneWidth) / total_width,
                        colTwoWidth      = 100 - colOneWidth,
                        colThreeWidth    = 0;

                        if (columnsNumber === 3) {
                            colTwoWidth   = (100*columnTwoWidth) / total_width;
                            colThreeWidth = 100 - (colOneWidth + colTwoWidth);
                        }

                    self.field.inner_columns_size['column-1'] = colOneWidth + '%';
                    self.field.inner_columns_size['column-2'] = colTwoWidth + '%';
                    self.field.inner_columns_size['column-3'] = colThreeWidth + '%';

                    columnElement = undefined;
                });
            })();
        }
    },

    watch: {
        innerColumns(new_value) {
            var columns = parseInt(new_value),
                columns_size = this.field.inner_columns_size;

            Object.keys(columns_size).forEach(function (column) {
                if (columns === 1) {
                    columns_size[column] = '100%';
                }

                if (columns === 2) {
                    columns_size[column] = '50%';
                }

                if (columns === 3) {
                    columns_size[column] = '33.33%';
                }
            });

            // if columns number reduce to 1 then move other column fields to the first column
            if ( columns === 1 ) {
                this.moveFieldsTo( "column-1" );
            }

            // if columns number reduce to 2 then move column-2 and column-3 fields to the column-2
            if ( columns === 2 ) {
                this.moveFieldsTo( "column-2" );
            }

            this.resizeColumns(columns);
        }
    }
}

</script>

<style scoped>

ul.contactum-form .contactum-field-columns {
  padding: 0;
  border: 0;
  overflow: hidden;
}
ul.contactum-form .contactum-field-columns.has-columns-1 .contactum-column .contactum-column-inner-fields {
  width: 100%;
  float: left;
}
ul.contactum-form .contactum-field-columns.has-columns-1 .contactum-column .contactum-column-inner-fields:nth-child(1) {
  padding-right: 0!important;
}
ul.contactum-form .contactum-field-columns.has-columns-1 .contactum-column .column-1 .ui-resizable-handle {
  display: none !important;
}
ul.contactum-form .contactum-field-columns.has-columns-1 .contactum-column .column-2.contactum-column-inner-fields,
ul.contactum-form .contactum-field-columns.has-columns-1 .contactum-column .column-3.contactum-column-inner-fields {
  display: none;
}
ul.contactum-form .contactum-field-columns.has-columns-2 .contactum-column .contactum-column-inner-fields {
  width: 50%;
  float: left;
}
ul.contactum-form .contactum-field-columns.has-columns-2 .contactum-column .contactum-column-inner-fields:nth-child(2) {
  padding-right: 0!important;
}
ul.contactum-form .contactum-field-columns.has-columns-2 .contactum-column .column-2 .ui-resizable-handle {
  display: none !important;
}
ul.contactum-form .contactum-field-columns.has-columns-2 .contactum-column .column-3.contactum-column-inner-fields {
  display: none;
}
ul.contactum-form .contactum-field-columns.has-columns-3 .contactum-column .contactum-column-inner-fields {
  width: 33.33%;
  float: left;
}
ul.contactum-form .contactum-field-columns.has-columns-3 .contactum-column .contactum-column-inner-fields:nth-child(3) {
  padding-right: 0!important;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns {
  margin-left: 0;
  margin-right: 0;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column {
  padding: 0;
  border: 0;
  float: none;
  width: 100%;
  display: flex;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields {
  padding: 0 5px 0 0;
  position: relative;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list {
  border: 1px dashed #ffb900;
  background: rgba(255, 185, 0, 0.08);
  margin: 0;
  padding: 0 0 50px 0;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items {
  /* background: #fff; */
  /* -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); */
  /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); */
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items.current-editing {
  background-color: rgba(255, 185, 0, 0.15);
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items:last-child {
  margin-bottom: 0;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items:hover .contactum-column-field-control-buttons {
  display: block;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items .contactum-column-field-control-buttons {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 10;
  display: none;
  width: 100%;
  height: 100%;
  margin: 0;
  text-align: center;
  background: rgba(255, 185, 0, 0.08);
  border: 1px dashed #ffb900;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items .contactum-column-field-control-buttons p {
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -10px 0 0 -43px;
  line-height: 1;
  color: #eee;
  background-color: #23282d;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items .contactum-column-field-control-buttons i {
  cursor: pointer;
  padding: 5px;
  font-size: 10px;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items .contactum-column-field-control-buttons i:hover {
  background-color: #0073aa;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields ul.contactum-column-fields-sortable-list li.column-field-items .contactum-column-field-control-buttons i.move {
  cursor: move;
}
ul.contactum-form .contactum-field-columns .contactum-column-field-inner-columns .contactum-column .contactum-column-inner-fields .drop-message {
  text-align: center;
  border: 1px dashed #ffb900;
  border-top: 0;
  background: rgba(255, 185, 0, 0.08);
  margin: 0;
  padding: 15px 0;
}
#form-preview-stage .field-items .contactum-field-columns + div.control-buttons {
  top: 10px;
  z-index: 10;
  height: auto;
  margin: 0;
  background: transparent;
  border: 0;
}

</style>