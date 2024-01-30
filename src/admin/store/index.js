import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

if (!Array.prototype.hasOwnProperty('swap')) {
    Array.prototype.swap = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
    };
}


function is_element_in_viewport (el) {
    if (typeof jQuery === "function" && el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

const store = new Vuex.Store({
	state: {
		field_settings: window.prafe.field_settings,
		panel_sections: window.prafe.panel_sections,
		form_fields: window.prafe.form_fields,
		post: window.prafe.post,
        settings: window.prafe.settings,
		current_panel: 'form_fields',
		editfield: 0
	},

	getters: {
		field_settings(state) {
			return state.field_settings;
		},
		panel_sections(state) {
			return state.panel_sections;
		},
		post(state) {
			return state.post;
		},
		form_fields(state) {
			return state.form_fields;
		},
		current_panel(state) {
			return state.current_panel;
		},
		editfield(state) {
			return state.editfield;
		},
        settings(state) {
            return state.settings
        }
	},

	mutations: {

		// add new form field element to column field
		add_column_inner_field_element: function add_column_inner_field_element(state, payload) {
			var columnFieldIndex = state.form_fields.findIndex(function (field) {
				return field.id === payload.toWhichColumnField;
			});

			if (state.form_fields[columnFieldIndex].inner_fields[payload.toWhichColumn] === undefined) {
				state.form_fields[columnFieldIndex].inner_fields[payload.toWhichColumn] = [];
			}

			if (state.form_fields[columnFieldIndex].inner_fields[payload.toWhichColumn] !== undefined) {
				var innerColumnFields = state.form_fields[columnFieldIndex].inner_fields[payload.toWhichColumn];

				if (innerColumnFields.filter(function (innerField) {
					return innerField.name === payload.field.name;
				}).length <= 0) {
					state.form_fields[columnFieldIndex].inner_fields[payload.toWhichColumn].splice(payload.toIndex, 0, payload.field);
				}
			}
		},

		move_column_inner_fields: function move_column_inner_fields(state, payload) {
			var columnFieldIndex = state.form_fields.findIndex(function (field) {
				return field.id === payload.field_id;
			}),
				innerFields = payload.inner_fields,
				mergedFields = [];

			Object.keys(innerFields).forEach(function (column) {
				// clear column-1, column-2 and column-3 fields if move_to specified column-1
				// add column-1, column-2 and column-3 fields to mergedFields, later mergedFields will move to column-1 field
				if (payload.move_to === "column-1") {
					innerFields[column].forEach(function (field) {
						mergedFields.push(field);
					});

					// clear current column inner fields
					state.form_fields[columnFieldIndex].inner_fields[column].splice(0, innerFields[column].length);
				}

				// clear column-2 and column-3 fields if move_to specified column-2
				// add column-2 and column-3 fields to mergedFields, later mergedFields will move to column-2 field
				if (payload.move_to === "column-2") {
					if (column === "column-2" || column === "column-3") {
						innerFields[column].forEach(function (field) {
							mergedFields.push(field);
						});

						// clear current column inner fields
						state.form_fields[columnFieldIndex].inner_fields[column].splice(0, innerFields[column].length);
					}
				}
			});

			// move inner fields to specified column
			if (mergedFields.length !== 0) {
				mergedFields.forEach(function (field) {
					state.form_fields[columnFieldIndex].inner_fields[payload.move_to].splice(0, 0, field);
				});
			}
		},

		// sorting inside column field
		swap_column_field_elements: function swap_column_field_elements(state, payload) {
			var columnFieldIndex = state.form_fields.findIndex(function (field) {
				return field.id === payload.field_id;
			}),
				fieldObj = state.form_fields[columnFieldIndex].inner_fields[payload.fromColumn][payload.fromIndex];

			if (payload.fromColumn !== payload.toColumn) {
				// add the field object to the target column
				state.form_fields[columnFieldIndex].inner_fields[payload.toColumn].splice(payload.toIndex, 0, fieldObj);

				// remove the field index from the source column
				state.form_fields[columnFieldIndex].inner_fields[payload.fromColumn].splice(payload.fromIndex, 1);
			} else {
				state.form_fields[columnFieldIndex].inner_fields[payload.toColumn].swap(payload.fromIndex, payload.toIndex);
			}
		},

		// open field settings panel
		open_column_field_settings: function open_column_field_settings(state, payload) {
			var field = payload.column_field;
			console.log(payload);
			console.log(state.current_panel);

			if ('field_options' === state.current_panel && field.id === state.editing_field_id) {
				return;
			}

			// if ('field-options' === state.current_panel && field.id === state.editing_field_id) {
			// 	return;
			// }

			if (field) {
				// state.editing_field_id = 0;
				// state.current_panel = 'field-options';
				state.current_panel = 'field_options';
				state.editing_field_type = 'column_field';
				state.editing_column_field_id = payload.field_id;
				state.edting_field_column = payload.column;
				state.editing_inner_field_index = payload.index;

				// setTimeout(function () {
					state.editing_field_id = field.id;
					state.editfield = field.id
				// }, 400);
			}
		},

		clone_column_field_element: function clone_column_field_element(state, payload) {
			var columnFieldIndex = state.form_fields.findIndex(function (field) {
				return field.id === payload.field_id;
			});

			var field = _.find(state.form_fields[columnFieldIndex].inner_fields[payload.toColumn], function (item) {
				return parseInt(item.id) === parseInt(payload.column_field_id);
			});

			var clone = jQuery.extend(true, {}, field),
				index = parseInt(payload.index) + 1;

			clone.id = payload.new_id;
			clone.name = clone.name + '_copy';
			clone.is_new = true;

			state.form_fields[columnFieldIndex].inner_fields[payload.toColumn].splice(index, 0, clone);
		},

		// delete a column field
		delete_column_field_element: function delete_column_field_element(state, payload) {
			var columnFieldIndex = state.form_fields.findIndex(function (field) {
				return field.id === payload.field_id;
			});

			state.current_panel = 'form-fields';
			state.form_fields[columnFieldIndex].inner_fields[payload.fromColumn].splice(payload.index, 1);
		},

        add_field(state, payload) {
            if( payload.toIndex ) {
                var index = payload.toIndex;
                delete payload.toIndex;
                state.form_fields.splice( index, 0, payload);
            } else {
                state.form_fields.splice( state.form_fields.length, 0, payload);
                var index = state.form_fields.length - 1;
            }
            // bring newly added element into viewport
            Vue.nextTick(function () {
                var el = jQuery('.form-preview-stage .prafe-form .field-items').eq( index );
                if (el && !is_element_in_viewport(el.get(0))) {
                    jQuery('.form-preview-stage section').scrollTo(el, 800, {offset: -200});
                }
            });
		},

		delete_field(state, payload) {
            state.current_panel = 'form_fields';
			return state.form_fields.splice(payload, 1);
		},

        set_current_panel(state,panel) {
            if ( 'field_options' !== state.current_panel && 'field_options' === panel && state.form_fields.length ) {
                    state.editfield = state.form_fields[0].id;
            }

            state.current_panel = panel;

            // reset editing field id
            if ('form_fields' === panel) {
                state.editfield = 0;
            }
        },

        select_field(state, payload) {
			let field = state.form_fields.filter((item) => payload === item.id );

			if ('field-options' === state.current_panel && field[0].id === state.editfield) {
			    return;
			}

			if (field.length) {
                 // state.editfield = field[0].id;
			     state.editfield = 0;
                 state.current_panel = 'field_options';
                // setTimeout(function () {
                //     state.editfield = payload;
                // }, 400);
                setTimeout(function () {
                    state.editfield = field[0].id;
                }, 400);
			}

			return state;
		},

		close_field_options(state, payload) {
			state.current_panel = 'form_fields';
			state.editfield = 0;

			return state;
		},

		duplicate_field(state, payload) {
			let index = payload.index + 1;
            delete payload['index'];
            state.form_fields.splice(index, 0, payload);
		},

        swap_form_field_elements( state, payload ) {
            state.form_fields.swap(payload.fromIndex, payload.toIndex);
        },

		update_editing_form_field: function(state, payload) {
			var i = 0;
			for (i = 0; i < state.form_fields.length; i++) {
				// check if the editing field exist in normal fields
				// if (state.form_fields[i].id == parseInt(payload.id)) {
					if (state.form_fields[i].id == payload.id) {
					console.log(payload);
					state.form_fields[i][payload.property] = payload.value;
				}

				// check if the editing field belong to a column field
				if (state.form_fields[i].template === 'column_field') {
					var innerColumnFields = state.form_fields[i].inner_fields;

					for (var columnFields in innerColumnFields) {
						if (innerColumnFields.hasOwnProperty(columnFields)) {
							var columnFieldIndex = 0;

							while (columnFieldIndex < innerColumnFields[columnFields].length) {
								if (innerColumnFields[columnFields][columnFieldIndex].id === parseInt(payload.id)) {
									innerColumnFields[columnFields][columnFieldIndex][payload.property] = payload.value;
								}
								columnFieldIndex++;
							}
						}
					}
				}
			}

			// let field = state.form_fields.find((field) => field.id == payload.id);
			// field[payload.property] = payload.value;
            // return state;
		},

		set_form_fields: function(state, payload) {
			Vue.set(state, 'form_fields', payload);
		},

        panel_toggle: function( state,payload ) {
        	console.log(payload);
        	console.log('payload');
        	console.log(state.panel_sections[payload]);
            state.panel_sections[payload].show = !state.panel_sections[payload].show;
        },

        set_panel_section_fields: function(state,payload) {

        },

        set_form_post: function( state, payload ) {
            Vue.set(state, 'post', payload);
        },


        set_form_settings: function( state, payload ) {
            Vue.set(state, 'settings', payload);
        },

	},
	actions: {
        swap_form_field_elements( context, payload ) {
            context.commit('swap_form_field_elements', payload);
        },

		add_field(context, payload) {
			context.commit('add_field', payload);
		},
		delete_field(context, payload) {
			context.commit('delete_field', payload);
		},
		select_field(context, payload) {
			context.commit('select_field', payload);
		},

		duplicate_field(context, payload) {
			context.commit('duplicate_field', payload);
		},

		update_editing_form_field: function(context, payload) {
			context.commit('update_editing_form_field', payload);
		},

		close_field_options: function(context, payload) {
			context.commit('close_field_options', payload);
		},

		set_form_fields: function(context, payload) {
			context.commit('set_form_fields', payload);
		},

        panel_toggle: function( context,payload ) {
            context.commit('panel_toggle', payload);
        },

        set_panel_section_fields: function(context,payload) {
            context.commit('set_panel_section_fields', payload);
        },

        set_form_post: function( context,payload ) {
            context.commit('set_form_post', payload);
        },

        set_form_settings: function( context,payload ) {
            context.commit('set_form_settings', payload);
        },

        set_current_panel:  function(context,payload) {
            context.commit('set_current_panel', payload);
        },
	}
});

export default store;
