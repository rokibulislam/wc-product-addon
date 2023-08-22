<template>
	<div class="contactum-fields">
	    <div class="contactum-math-captcha">
	        <ul class="captcha">
	            <li class="refresh">
	                <svg fill="#555555" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="tiny" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 480 480" xml:space="preserve" style="vertical-align: bottom;">
	                    <g>
	                        <path d="M383.434,172.242c-25.336-58.241-81.998-95.648-145.861-95.648c-65.309,0-125,40.928-148.514,101.827l49.5,19.117   c15.672-40.617,55.469-67.894,99.014-67.894c42.02,0,79.197,24.386,96.408,62.332l-36.117,14.428l92.352,53.279l27.01-100.933   L383.434,172.242z"></path>
	                        <path d="M237.573,342.101c-41.639,0-79.615-25.115-96.592-62.819l35.604-13.763l-91.387-52.119l-27.975,98.249l34.08-13.172   c24.852,58.018,82.859,96.671,146.27,96.671c65.551,0,123.598-39.336,147.871-100.196l-49.268-19.652   C319.981,315.877,281.288,342.101,237.573,342.101z"></path>
	                    </g>
	                </svg>
	            </li>
	            <li class="captcha-number-area">
	                <p class="captcha-number">
                    <span v-html="captcha.operandOne"></span>
                    <span v-html="captcha.operator"></span>
                    <span v-html="captcha.operandTwo"></span>
	                </p>
	            </li>
	            <li class="captcha-equal">=</li>
	            <li>
	                <input
	                    type="text"
	                    :class="class_names('textfield')"
	                    :placeholder="field.placeholder"
	                    :value="field.default"
	                    :size="field.size"
	                >
	            </li>
	        </ul>
	    </div>
	    
	    <span v-if="field.help" class="contactum-help" v-html="field.help" />
	</div>
</template>

<script>

import form_field from "../../mixin/form-field.js";

export default {
  name: "form_math_captcha",
  mixins: [form_field],
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
    },
    captcha: () => {
        let operators = [ '+', '-', 'x' ],
            random = Math.floor( Math.random() * operators.length );

        return {
            operandOne: Math.floor( Math.random() * 200 ) + 1,
            operandTwo: Math.floor( Math.random() * 200 ) + 1,
            operator: operators[random]
        }  
    }
  },
};
</script>
