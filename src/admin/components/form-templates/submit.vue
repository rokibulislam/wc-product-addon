<template>
    <div :class="['text-' + field.alignment ]">
        <!-- {{  field }} -->
        <button 
            type="type"
            class="submit-btn"
            :class="[btnSize]"
            :style="btnStyles"
        > 
            {{ field.button_text }} 
        </button>
        <!-- <div class="control-button">
            <button @click.prevent="select_field(field)">
                <i class="fa fa-pencil"></i>
            </button>
        </div> -->
    </div>
</template>

<script>

    import form_field from "../../mixin/form-field.js";

    export default {
        name: "form_submit_field",
        mixins: [form_field],
        computed: {
            btnStyles() {
                let currentState =  this.field.currentState;

                if( this.field.button_style == 'custom' ) {

                    if( currentState == "normal") {
                        return {
                            backgroundColor: this.field.settings.normal_background_color,
                            color: this.field.settings.normal_font_color,
                            border: `1px solid ${this.field.settings.normal_border_color}`,
                            borderRadius:  `${this.field.settings.normal_border_radius}px`,
                            minWidth: `${this.field.settings.normal_minwidth}`,
                        }
                    } else {
                      return {
                        backgroundColor: this.field.settings.hover_background_color,
                        color: this.field.settings.hover_font_color,
                        border: `5px solid ${this.field.settings.hover_border_color}`,
                        borderRadius:  `${this.field.settings.normal_border_radius}px`,
                        minWidth: `${this.field.settings.hover_minwidth}`
                      }  
                    }

                } else {
                    let style = this.field.button_style
                    return {
                        backgroundColor:  style == 'default' ? '#1a7efb' : style,
                        color: '#fff',
                        border: 'none',
                    }
                }
            },
            
            btnStyleClass() {
                return this.field.button_style;
            },

            btnSize() {
                return 'submit-btn-' + this.field.button_size
            }
        },
        methods: {
            select_field(field) {
                this.$store.dispatch("select_field", field.id);
            },
        }
    };

</script>

<style>

.text-center {
    text-align: center;
}

.text-left {
    text-align: left;
}

.text-right {
    text-align: right;
}

.submit-btn-large {
    border-radius: 6px;
    font-size: 18px;
    line-height: 1.5;
    padding: 8px 16px;
}

.submit-btn-small {
    border-radius: 3px;
    font-size: 13px;
    line-height: 1.5;
    padding: 4px 8px;
}

.submit-btn-medium {
    border-radius: 3px;
    font-size: 13px;
    line-height: 1.5;
    padding: 4px 8px;
}

</style>