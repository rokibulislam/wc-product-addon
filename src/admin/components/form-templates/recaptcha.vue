<template>
  <div class="contactum-fields">
    <template v-if="!has_recaptcha_api_keys">
        <p> No Api key </p>
    </template>

    <template v-else>
        <div v-if="'invisible_recaptcha' != field.recaptcha_type">
                Visiable Recaptcha
        </div>
        <div v-else><p> Invisible reCaptcha </p></div>
    </template>
  </div>
</template>
<script>
import form_field from "../../mixin/form-field.js";
export default {
    name: "form_recaptcha",
    mixins: [form_field],
    methods: {
        has_recaptcha_api_keys: function() {
            return (contactum.recaptcha_public && contactum.recaptcha_secret) ? true : false;
        }
    }
};
</script>
