<template>
    <div class="contactum-fields">
        <div class="br-wrapper br-theme-css-stars">
            <div class="br-widget">
                 <a v-for="option in field.options" href="#"> </a>
            </div>
            <span v-if="field.help" class="contactum-help" v-html="field.help" />
        </div>
    </div>
</template>

<script>

import form_field from "../../../mixin/form-field.js";

export default {
  name: "form_rating_field",
  mixins: [form_field],
};

</script>

